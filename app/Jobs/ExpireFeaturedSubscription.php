<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\UserFeaturedSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ExpireFeaturedSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $subscriptionId;

    public function __construct($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscription = UserFeaturedSubscription::find($this->subscriptionId);
        if ($subscription) {
            $subscription->update([
                'is_expired' => 1,
            ]);

            $product = Product::find($subscription->product_id);
            if($product){
                $product->update([
                    'is_featured'=> false
                ]);
            }
        }

    }
}
