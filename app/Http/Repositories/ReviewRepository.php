<?php


namespace App\Http\Repositories;

use App\Http\Repositories\BaseRepository\Repository;
use App\Models\Review;
use Exception;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReviewRepository extends Repository
{

    protected OrderDetailRepository $orderDetailRepository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->orderDetailRepository = new OrderDetailRepository();
        $this->userRepository = new UserRepository();
        $this->setModel(new Review());
    }

    public function save($request, $id = 0)
    {
        DB::beginTransaction();
        try {
            $this->getUser();
            $query = $this->getModel()->query();
            $data = $request->except('_token');
            $productId = 0;

            if ($request->product_id) {
                $data['product_id'] = $request->product_id;
                $productId = $request->product_id;
            }

            $review = $query->updateOrCreate(['id' => $id], $data);

            $query = $this->getModel()->query();
            if ($request->order_detail_id != null) {
                $storeId = $request->store_id;
                $query->with(['orderDetails' => function ($q) use ($storeId) {
                    $q->where('store_id', '=', $storeId);
                }])->where('store_id', $storeId)->where('order_detail_id', '!=', null);
                $averageRating = $query->avg('rating');
                if (is_null($averageRating)) {
                    $averageRating = 0;
                }
                $user = $this->userRepository->getquery();
                $user->where('id', '=', $storeId)->update(['rating' => $averageRating]);
                $notificationArray = ['sender_id' => $this->user->id, 'receiver_id' => $request->store_id, 'store_id' => $request->store_id, 'title->en' => 'Rating & reviews', 'title->ar' => 'التقييمات والمراجعات', 'description->en' => 'New review has given to your store', 'description->ar' => 'أعطت مراجعة جديدة لمتجرك', 'action' => 'STORE_REVIEWS_DASH'];
                $this->notification($notificationArray);
            } else {
                if ($productId != 0) {
                    $query->with(['orderDetailItems' => function ($q) use ($productId) {
                        $q->with(['review']);
                    }])->where('product_id', '=', $productId);
                    $averageRating = $query->avg('rating');
                    $product = Product::where('id', '=', $productId)->first();
                    $product->update(['average_rating' => $averageRating]);
                    $notificationArray = ['sender_id' => $this->user->id, 'receiver_id' => $request->store_id, 'product_slug' => $product->slug,'product_id' => $product->id, 'title->en' => 'Rating & reviews', 'title->ar' => 'التقييمات والمراجعات', 'description->en' => 'New review has given to your product', 'description->ar' => 'أعطت مراجعة جديدة لمنتجك', 'action' => 'PRODUCT_REVIEWS'];
                    $this->notification($notificationArray);
                }
            }
            DB::commit();
            return $review;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function all($params)
    {
        $query = $this->getModel()->query()->with('user:id,user_name,image,user_type');
        if (isset($params['product_id']) && $params['product_id'] != '') {
            $productId = $params['product_id'];
            $query->wherehas('product', function ($q) use ($productId) {
                $q->where('product_id', '=', $productId)->where('order_detail_items_id', '!=', null)->latest();
            });
        }

        if (isset($params['store_id']) && $params['store_id'] != '') {
            $storeId = $params['store_id'];
            $query->wherehas('store', function ($q) use ($storeId) {
                $q->where('store_id', '=', $storeId)->where('order_detail_id', '!=', null)->latest();
            });
        }
        if ($this->getPaginate() > 0) {
            return $query->orderBy('id', 'desc')->paginate($this->getPaginate());
        } else {
            return $query->orderBy('id', 'desc')->get();
        }
    }

    public function notification($notificationArray)
    {
        if (isset($notificationArray['product_slug'])) {
            sendNotification([
                'sender_id' => $notificationArray['sender_id'],
                'receiver_id' => $notificationArray['receiver_id'],
                'extras->product_slug' => $notificationArray['product_slug'],
                'extras->product_id' => $notificationArray['product_id'],
                'title->en' => $notificationArray['title->en'],
                'title->ar' => $notificationArray['title->ar'],
                'description->en' => $notificationArray['description->en'],
                'description->ar' => $notificationArray['description->ar'],
                'action' => $notificationArray['action']
            ]);
        } else {
            sendNotification([
                'sender_id' => $notificationArray['sender_id'],
                'receiver_id' => $notificationArray['receiver_id'],
                'extras->store_id' => $notificationArray['store_id'],
                'title->en' => $notificationArray['title->en'],
                'title->ar' => $notificationArray['title->ar'],
                'description->en' => $notificationArray['description->en'],
                'description->ar' => $notificationArray['description->ar'],
                'action' => $notificationArray['action']
            ]);
        }

    }
}
