<?php

namespace App\Http\Controllers\Api;

use App\Http\Repositories\ReviewRepository;
use App\Http\Requests\ReviewRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;

class ReviewController extends Controller
{
    protected ReviewRepository $reviewRepository;
    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->middleware('jwt.verify')->only('save');
        $this->reviewRepository = new ReviewRepository;
        $this->userRepository = new UserRepository;
    }

    public function get(Request $request)
    {
        $params = $request->all();
        $this->reviewRepository->setPaginate(8);
        $reviews = $this->reviewRepository->all($params);
        $reviews_data['reviews'] = $reviews;
        $reviews_data['reviews_count'] = count($reviews);
        return responseBuilder()->success(__('All reviews.'), $reviews_data);
    }

    public function save(ReviewRequest $request)
    {
        $review = $this->reviewRepository->save($request, $id = 0);
        if ($review) {
            return responseBuilder()->success(__('Review added successfully.'));
        }
    }
}
