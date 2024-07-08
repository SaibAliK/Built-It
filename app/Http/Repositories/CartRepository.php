<?php


namespace App\Http\Repositories;

use App\Events\CartChangeNotifications;
use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Cart;
use Exception;
use App\Models\AreaUser;
use ErrorException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CartRepository extends Repository
{
    protected ProductRepository $productRepository;

    public function __construct()
    {
        $this->setModel(new Cart());
        $this->productRepository = new ProductRepository();
    }

    public function save($request, $fromMobileSide = false)
    {
        DB::beginTransaction();
        try {
            //check cart already exist
            $userCart = $this->getModel()->where('user_id', $this->getUser()->id)->get();

            $this->productRepository->setRelations(['images' => function ($image) {
                $image->where('file_default', 1);
            }]);

            //get product detail which are in cart
            $product = $this->productRepository->get($request->product_id, null, null, null, $request);

            $quantity = $product->quantity;
            $price = $product->discounted_price;
            $image = '';

            if (count($product->images) > 0) {
                $image = $product->images[0]->file_path;
            }

            $id = 0;
            if ($quantity < $request->quantity) {
                throw new Exception(__('This product is out off stock'));
            }

            //request data include quantity and product
            $data['product_id'] = $request->product_id;


            if ($fromMobileSide) {
                //get the area price for mobile side
                $delivery_price = $product->store->areas()->where('area_id', $request->area_id)->first();
                $shipping_price_from_area = $delivery_price->pivot->price ?? 0;
            }


            //check if same product already are in cart then update its quantity and subtotal
            $cartsExist = $this->getModel()->where([['user_id', $this->getUser()->id], ['product_id', $data['product_id']]])->first();
            if (!$cartsExist) {
                $data['price'] = $price;
                $data['images'] = $image;
                $data['quantity'] = $request->quantity;
                $data['user_id'] = $this->user->id;
                $data['store_id'] = $product->user_id;
                $data['subtotal'] = $data['price'] * $data['quantity'];
                if (!$this->getModel()->where([['user_id', $this->getUser()->id], ['store_id', $product->store->id], ['shipping', '>', 0]])->first()) {
                    if ($fromMobileSide) {
                        $data['shipping'] = $shipping_price_from_area;
                    } else {
                        $data['shipping'] = 0;
                        //   $data['shipping'] = $product->store->getDeliveryPriceAttribute();
                    }
                }
                $data['product_id'] = $product->id;
                $data['extras'] = '';
                $data['total'] = $data['subtotal'] + 0;
                //  $data['total'] = $data['subtotal'] + $product->store->getDeliveryPriceAttribute();
            } else {
                $id = $cartsExist->id;
                if (!$this->getModel()->where([['user_id', $this->getUser()->id], ['store_id', $product->store->id], ['shipping', '>', 0]])->first()) {
                    if ($fromMobileSide) {
                        $data['shipping'] = $shipping_price_from_area;
                    } else {
                        $data['shipping'] = 0;
                        //  $data['shipping'] = $product->store->getDeliveryPriceAttribute();
                    }
                }
                $data['quantity'] = $cartsExist->quantity + $request->quantity;
                $data['subtotal'] = $cartsExist->price * $data['quantity'];
                $data['total'] = $data['subtotal'] + $cartsExist->shipping;
            }

            //update cart for new ond already cart
            $cart = $this->getModel()->updateOrCreate(['id' => $id], $data);

            //update product for remaining quantity
            if ($product->quantity >= $request->quantity) {
                $productUpdate = [
                    "quantity" => $cart->product->quantity - $request->quantity,
                    'reserve' => $cart->product->reserve + $request->quantity
                ];
                $this->productRepository->getModel()->where('id', $request->product_id)->update($productUpdate);
            }
            event(new CartChangeNotifications($this->getCartCount(), $this->getUser()->id));

            DB::commit();
            return $cart;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getCartCount()
    {
        $user = $this->getUser();
        $userId = $user->id;
        return $this->getModel()->whereHas('product')->with('product')->where('user_id', $userId)->sum('quantity');
    }


    public function update($request)
    {
        DB::beginTransaction();
        try {
            $cartItem = $request->get('card_item');
            $cartIds = array_column($cartItem, 'card_id');
            $carts = $this->getModel()->whereHas('product')->with(['product'])->whereIn('id', $cartIds)->get();

            if (count($carts) != count($cartItem)) {
                return false;
            }

            foreach ($cartItem as $index => $input) {
                $cart = $carts[$index];

                $quantityLeft = $input['quantity'] - $cart->quantity;

                //for update cart
                $mainQuantity = intval($input['quantity']);
                $updateCart = [
                    'subtotal' => $cart->price * $mainQuantity,
                    'quantity' => $mainQuantity,
                    'total' => $cart->price * $mainQuantity + $cart->shipping
                ];

                $totalProductQuantity = $cart->product->quantity + $cart->product->reserve;

                if ($totalProductQuantity == 0 || $input['quantity'] == 0) {
                    continue;
                }
                if ($mainQuantity <= 0) {
                    throw new Exception(__('This product is out off stock'));
                }
                if ($totalProductQuantity < $mainQuantity) {
                    throw new Exception(__('This product is out off stock'));
                }
                if ($input['quantity'] == $cart->quantity) {
                    continue;
                }

                $updatedQuantity = 0;
                if ($quantityLeft > 0) {
                    if ($cart->product->attributesProduct) {
                        $updatedQuantity = $cart->product->attributesProduct->quantity - $quantityLeft;
                    }
                    $productUpdatedQuantity = $cart->product->quantity - $quantityLeft;
                    $updatedReserved = $cart->product->reserve + $quantityLeft;
                } else {
                    if ($cart->product->attributesProduct) {
                        $updatedQuantity = $cart->product->attributesProduct->quantity + abs($quantityLeft);
                    }
                    $productUpdatedQuantity = $cart->product->quantity + abs($quantityLeft);
                    $updatedReserved = $cart->product->reserve - abs($quantityLeft);
                }
                $productUpdate = [
                    'quantity' => $productUpdatedQuantity,
                    'reserve' => $updatedReserved
                ];

                //update cart in loop
                $this->getModel()->where('id', $input['card_id'])->update($updateCart);

                //update each product in loop
                $this->productRepository->getQuery()->where('id', $cart->product_id)->update($productUpdate);

            }
            event(new CartChangeNotifications($this->getCartCount(), $this->getUser()->id));
            DB::commit();
            return $this->all();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete($id = null, $purchased = false, $productUpdate = false, $storeId = null)
    {
        DB::beginTransaction();
        try {
            $data = [];
            $data['id'] = $id;

            if (!empty($storeId)) {
                $data['id'] = $storeId;
            }

            $data['index'] = 'id';

            if ($purchased) {
                $data['index'] = 'user_id';
            } else if ($productUpdate) {
                $data['index'] = 'product_id';
            }
            if (!empty($storeId)) {
                $data['index'] = 'store_id';
            }

            $query = $this->getQuery();
            $productQuery = $this->productRepository->getQuery();
            $carts = $query->whereHas('product')->with('product')->where($data['index'], $data['id'])->get();
            if (empty($carts)) {
                return false;
            }
            foreach ($carts as $cart) {
                $reserve = $cart->product->reserve - $cart->quantity;
                if ($purchased) {
                    $productUpdate = [
                        "sold" => $cart->product->sold + $cart->quantity,
                        'reserve' => $reserve
                    ];

                } else {
                    $productUpdate = [
                        "quantity" => $cart->product->quantity + $cart->quantity,
                        'reserve' => $reserve
                    ];
                }
                $productQuery->where('id', $cart->product_id)->update($productUpdate);

                sendNotification([
                    'sender_id' => 2,
                    'receiver_id' => $cart->user_id,
                    'extras->conversation_id' => 0,
                    'extras->product_id' => $cart->product->id,
                    'title->en' => 'Product Deleted',
                    'title->ar' => 'Product Deleted',
                    'description->en' => 'Product has been Deleted.',
                    'description->ar' => 'Product has been Deleted.',
                    'action' => 'nothing'
                ]);

            }


            $query->where($data['index'], $data['id'])->delete();
            event(new CartChangeNotifications($this->getModel()->whereHas('product')->where('user_id', $this->getUser()->id)->sum('quantity'), $this->getUser()->id));
            DB::commit();
            return $this->all();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function empty($id = null, $purchased = false, $productUpdate = false, $storeId = null)
    {
        DB::beginTransaction();
        try {
            $data = [];
            $data['id'] = $id;

            if (!empty($storeId)) {
                $data['id'] = $storeId;
            }

            $data['index'] = 'id';

            if ($purchased) {
                $data['index'] = 'user_id';
            } else if ($productUpdate) {
                $data['index'] = 'product_id';
            }
            if (!empty($storeId)) {
                $data['index'] = 'store_id';
            }

            $query = $this->getQuery();
            $productQuery = $this->productRepository->getQuery();
            $carts = $query->whereHas('product')->with('product')->where($data['index'], $data['id'])->get();
            if (empty($carts)) {
                return false;
            }
            $query->where($data['index'], $data['id'])->delete();
            DB::commit();
            return $this->all();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function all()
    {
        $user = $this->getUser();
        $carts = $this->getModel()->query();
        $store = function ($query) {
            $query->select('id', 'supplier_name');
        };
        $product = function ($query) {
            $query->select('id', 'name', 'slug', 'user_id', 'price', 'average_rating');
        };
        if (!empty($this->getRelations())) {
            $carts = $carts->whereHas('product')->with($this->getRelations())->where('user_id', $user->id)->get();
        } else {
            $carts = $carts->whereHas('product')->whereHas('store')->with(['product' => $product, 'store' => $store])->where('user_id', $user->id)->get();
        }

        $pricesObject = new stdClass();
        $pricesObject->subtotal = 0;
        $pricesObject->subtotalWithDiscount = 0;
        $pricesObject->shipping = 0;
        $pricesObject->discount = 0;
        $pricesObject->vat = 0;
        $pricesObject->discountPercentage = 0;
        $pricesObject->maxDiscount = 0;
        $pricesObject->vatPercentage = 0;
        $pricesObject->total = 0;
        $pricesObject->couponUsed = false;
        $pricesObject->couponCode = '';
        $pricesObject->allowCoupon = false;
        $pricesObject->removeCoupon = false;


        if (isset($user->coupon) && !empty($user->coupon)) {
            $coupon_discount = 0;
            $pricesObject->couponUsed = true;
            if ($coupon_discount) {
                $pricesObject->discountPercentage = $coupon_discount;
                $pricesObject->couponCode = $user->coupon;
            } else {
                $pricesObject->removeCoupon = true;
                $pricesObject->discountPercentage = 0;
            }
        }


        if (count($carts) > 0) {
            foreach ($carts as $key => $cart) {
                $cart->discount = 0;

                if (isset($coupon_discount) && $coupon_discount != 0) {
                    $cart->discount = $coupon_discount;
                }
                $pricesObject->allowCoupon = true;

                if ($pricesObject->couponUsed && !$pricesObject->removeCoupon) {
                    $discountedAmount = $cart->subtotal * ($pricesObject->discountPercentage / 100);
                    $pricesObject->discount += $discountedAmount;
                    $cart->discount = $discountedAmount;
                    $pricesObject->subtotal += ($cart->subtotal);
                } else {
                    $pricesObject->subtotal += $cart->subtotal;
                }

                $cart->allow_coupon = $cart->product->allow_coupon;

                $cart->price = getPriceObject($cart->price);
                $cart->subtotal = getPriceObject($cart->subtotal);
                $cart->discount = getPriceObject($cart->discount);

                $cart->total = getPriceObject($cart->total);
            }
        }
        $shipping = 0;
        if (count($carts) > 0) {
            if (!is_null($user->defaultAddress)) {
                $areaShipping = AreaUser::select('price')->where('area_id', $user->defaultAddress->area_id)->where('user_id', $carts->first()->store->id)->first();
                if (!is_null($areaShipping)) {
                    $shipping = $areaShipping->price;
                }

            }
        }

        $cartObject = [];
        $pricesObject->subtotalWithDiscount = $pricesObject->subtotal - $pricesObject->discount;
        $pricesObject->vat = $pricesObject->subtotalWithDiscount * (config('settings.value_added_tax') / 100);
        $pricesObject->vatPercentage = config('settings.value_added_tax');
        $pricesObject->total = $pricesObject->subtotalWithDiscount + $pricesObject->vat + $shipping;
        $pricesObject->subtotal = getPriceObject($pricesObject->subtotal);
        $pricesObject->subtotalWithDiscount = getPriceObject($pricesObject->subtotalWithDiscount);
        $pricesObject->discount = getPriceObject($pricesObject->discount);
        $pricesObject->shipping = getPriceObject($shipping);
        $pricesObject->vat = getPriceObject($pricesObject->vat);
        $pricesObject->total = getPriceObject($pricesObject->total);
        $cartObject['list'] = $carts->flatten();
        $cartObject['price_object'] = $pricesObject;
        return $cartObject;
    }

    public function isEmpty()
    {
        $user = $this->getUser();
        $data = $this->getQuery()->where('user_id', $user->id)->get();
        if (count($data) > 0) {
            return false;
        }
        return true;
    }

    public function getAll()
    {
        return $this->getModel()->all();
    }

    public function getCartUser($user_id)
    {
        return $this->getModel()->where('user_id', $user_id)->first();
    }

}
