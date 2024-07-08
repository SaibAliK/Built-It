<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Repositories\OrderDetailRepository;
use App\Http\Repositories\OrderDetailItemRepository;
use App\Http\Repositories\UserRepository;
use App\Http\Repositories\ReviewRepository;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;

class ReviewController extends Controller
{
    protected ReviewRepository $reviewRepository;

    public function __construct()
    {
        parent::__construct();
        $this->reviewRepository = new ReviewRepository();
        $this->breadcrumbs[route('front.index')] = ['title' => __('Home')];
    }

    public function get(Request $request)
    {
        $this->breadcrumbTitle = __('Ratings & Reviews');
        $this->breadcrumbs['javascript:{};'] = ['title' => __('Ratings & Reviews')];
        $this->reviewRepository->setPaginate(6);
        $reviews = $this->reviewRepository->all(['store_id' => auth()->id()]);
        return view('front.reviews.index', ['reviews' => $reviews]);
    }

    public function save(ReviewRequest $request)
    {
        try {
            $review = $this->reviewRepository->save($request, $id = 0);
            if ($review) {
                if (isset($request->product_id)) {
                    return redirect()->route('front.product.detail', $request->product_id)->with('status', __('Review added successfully.'));
                } else {
                    return redirect()->route('front.supplier.detail', ['id' => $request->store_id, '/add-review' => true])->with('status', __('Review added successfully.'));
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }
}
