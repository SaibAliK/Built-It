<?php

namespace App\Http\Controllers\Front;

use App\Http\Repositories\CartRepository;
use App\Http\Repositories\ProductRepository;
use App\Http\Requests\CartRequest;
use Illuminate\Http\Request;
use App\Http\Dtos\AddToCartDto;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;


class CartController extends Controller
{
    protected CartRepository $cartRepository;
    protected ProductRepository $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->cartRepository = new CartRepository();
        $this->productRepository = new ProductRepository();
    }

    public function index()
    {
        $this->breadcrumbTitle = __('Add To Cart');
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
        $this->breadcrumbs['javascript: {};'] = ['title' => __('Add To Cart')];
        if ($this->user) {
            $this->cartRepository->setRelations(['product', 'store:id,supplier_name,user_type,email']);
            $carts = $this->cartRepository->all();
        } else {
            $carts = Session()->get('cart_data');
        }

        return view('front.cart.cart', ['cart' => $carts]);
    }

    public function save(CartRequest $request)
    {

        try {
            $cart = $this->cartRepository->getCartUser(auth()->user()->id);
            if(!empty($cart)){
              $product =  $this->productRepository->get($request->product_id);
                if($cart->store_id !=  $product->user_id ){
                    throw new Exception(__('Please Select Product Of The Same Supplier'));
                }
            }
            $addToCartDTo = AddToCartDto::fromRequest($request);
            $cart = $this->cartRepository->save($addToCartDTo);
            if ($cart) {
                session()->put('cart', $cart['cart_count']);
                return redirect()->route('front.dashboard.cart.index')->with('status', __('Product added to cart'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }
    }

    public function update(CartRequest $request)
    {
        try {
            if ($this->user) {
                $carts = $this->cartRepository->update($request);
                if ($carts == false) {
                    return redirect()->back()->with('err', __('Product is Update So cart is deleted.'));
                }
            } else {
                $carts = null;
            }
            return redirect()->back()->with('status', __('Cart updated successfully'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->cartRepository->delete($id);
            return redirect()->route('front.dashboard.cart.index')->with('status', __('Item is Successfully deleted from cart.'));
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }
}
