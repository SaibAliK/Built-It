<?php

namespace App\Jobs;

use App\Models\AttributeProduct;

//use App\Models\Cart;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductDiscountExpiry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $productId;

    public function __construct($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product = Product::find($this->productId);
        $price_without_discount = $product->price;
        if ($product->getDiscountIsExpiredAttribute()) {
            $product->update([
                'offer_percentage' => 0,
                'offer_expiry_date' => '',
//                'product_type' => 'offer',
                'discounted_price' => $price_without_discount
            ]);
             app('App\Http\Repositories\CartRepository')->delete($this->productId,false,true);
        }
    }
}
