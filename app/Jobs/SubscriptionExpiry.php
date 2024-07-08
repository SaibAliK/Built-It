<?php

namespace App\Jobs;

use App\Http\Repositories\CouponRepository;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\SubscriptionPackage;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\SubscriptionPackageRepository;

use App\Http\Repositories\UserSubscriptionRepository;
class SubscriptionExpiry implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $userId;


    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find($this->userId);
        $todayDate = Carbon::parse(now())->format("U");
        if (!is_null($user->expiry_date)){
            if ($user->expiry_date <= $todayDate){
                UserSubscription::where('user_id', $this->userId)->update(['is_expired' => 1]);
                $user->update(['expiry_date' => null]);
                sendNotification([
                    'sender_id' => 2,
                    'receiver_id' => $this->userId,
                    'extras->conversation_id' => 0,
                    'title->en' => 'Subscription Expired',
                    'title->ar' => 'Subscription Expired',
                    'description->en' => 'Your subscription has expired.',
                    'description->ar' => 'Your subscription has expired.',
                    'action' => 'SUBSCRIPTION_EXPIRED'
                ]);
            }
        }
    }
}
