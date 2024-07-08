<?php

namespace App\Actions;

use App\Http\Dtos\AddProductDto;
use App\Http\Repositories\ProductImageRepository;
use App\Jobs\ExpireFeaturedSubscription;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\UserFeaturedSubscription;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Jobs\ProductDiscountExpiry;
use Exception;
use App\Jobs\ExpireOffer;


class AddProduct
{
    public Product $getModel;
    protected ProductImageRepository $imageRepository;

    public function __construct()
    {
        $this->getModel = new Product();
        $this->imageRepository = new ProductImageRepository();
    }

    public function handle(AddProductDto $params, $id): Product
    {
        DB::beginTransaction();
        try {

            $data = $params->except('product_images', 'featured_package_id')->toArray();
            $data['user_id'] = auth()->user()->id;

            if ($data['id'] == 0) {
                $data['slug'] = $this->getModel->checkSlug($data['name']['en']);
            } else {
                unset($data['slug']);
            }

            if ($data['allowOffer'] == true) {
                $data['product_type'] = 'offer';
                $data['status'] = 'pending';
                $discountedAmount = $params->price * ($params->offer_percentage / 100);
                $data['discounted_price'] = $params->price - $discountedAmount;
            } else {
                $data['product_type'] = 'product';
                $data['status'] = 'null';
                $data['offer_percentage'] = 0;
                $data['offer_expiry_date'] = '';
                $data['discounted_price'] = $params->price;
            }

            if ($params->featured_package_id != 0) {
                $data['is_featured'] = true;
            } else {
                $data['is_featured'] = false;
            }

            $product = $this->getModel->updateOrCreate(['id' => $params->id], $data);

            if ($product->offer_percentage > 0) {
                $days = Carbon::now()->diffInDays(unixTODateformate($product->offer_expiry_date));
                ProductDiscountExpiry::dispatch($product->id)->delay(now()->addDays($days)->endOfDay());
            }

            if ($product->product_type == 'offer') {
                if ($product->offer_expiry_date && $product->offer_expiry_date != 0) {
                    $days = Carbon::now()->diffInDays(unixTODateformate($product->offer_expiry_date));
                    ExpireOffer::dispatch($product->id)->delay(now()->addDays($days)->endOfDay());
                }
            }

            //            $cartRepository = new CartRepository();
//            $carts = $cartRepository->getModel()->where('product_id', $product->id)->get();
//            $cartUserId = [];
//
//            foreach ($carts as $cart) {
//                array_push($cartUserId, $cart->user_id);
//                $cart->delete();
//            }
//            foreach ($cartUserId as $id) {
//                sendNotification([
//                    'sender_id' => \Auth::check() ? auth()->id() : 1,
//                    'receiver_id' => $id,
//                    'extras->product_slug' => $product->slug,
//                    'title->en' => 'Product has been removed from your cart.',
//                    'title->ar' => __('Product has been removed from your cart.'),
//                    'description->en' => 'Product has been removed from your cart.',
//                    'description->ar' => __('Product has been removed from your cart.'),
//                    'action' => 'CART'
//                ]);
//                event(new CartChangeNotifications($cartRepository->getModel()->whereHas('product')->where('user_id', $id)->sum('quantity'), $id));
//            }

            if ($params->featured_package_id && $params->featured_package_id != '') {
                $this->makeFeatured($params->featured_package_id, $product->id);
            }

            //Delete Old Images
            if ($params->id && $params->id != 0) {
                $productImages = $product->images()->get();
                if (count($productImages) > 0) {
                    $this->deleteOldImage($productImages);
                }
            }

            //save new Images
            $this->SaveImages($params->product_images, $product->id);

            DB::commit();
            return $product;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }

    }

    public function makeFeatured($package_id, $product_id)
    {
        $subscription = UserFeaturedSubscription::findOrFail($package_id);
        $subscription->update(['product_id' => $product_id]);
        $expiry_date = unixConversion($subscription->package['duration_type'], $subscription->package['duration'], now()->unix());
        $days = Carbon::now()->diffInDays(Carbon::parse($expiry_date));
        ExpireFeaturedSubscription::dispatch($subscription->id)->delay(now()->addDays($days)->endOfDay());
    }

    public function SaveImages($images, $product_id)
    {
        foreach ($images as $image) {
            ProductImage::create([
                'product_id' => $product_id,
                'file_path' => $image['file_path'],
                'file_type' => $image['file_type'],
                'file_default' => ($image['file_default'] == "1" || $image['file_default'] == true) ? 1 : 0,
            ]);
        }
    }

    public function deleteOldImage($images)
    {
        foreach ($images as $image) {
            $image->delete();
        }
    }
}
