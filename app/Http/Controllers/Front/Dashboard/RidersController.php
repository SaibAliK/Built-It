<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Repositories\SubscriptionPackageRepository;
use App\Http\Repositories\CategoryRepository;
use App\Http\Repositories\UserRepository;
use  App\Http\Repositories\CityRepository;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Http\Dtos\UserRegisterDto;


class RidersController extends Controller
{
    protected object $packageRepositorym, $cityRepository;
    protected object $categoryRepository, $userRepository;

    public function __construct(CityRepository $cityRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->cityRepository = $cityRepository;
        $this->breadcrumbs[0] = ['url' => route('front.dashboard.index'), 'title' => __('Home')];
    }

    public function index()
    {
        try {
            $this->breadcrumbTitle = __('Manage riders');
            $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Manage riders')];

            $this->userRepository->setPaginate(4);
            $riders = $this->userRepository->riders(auth()->user()->id);
            return view('front.dashboard.rider.index', ['riders' => $riders]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['err' => $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $rider = $this->userRepository->get($id);
            $cities = $this->cityRepository->all();

            $this->breadcrumbTitle = __('Add Rider');
            $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Add Rider')];

            if ($id > 0) {
                if (!is_null($rider)) {
                    if ($rider->company_id != auth()->user()->id) {
                        return redirect()->route('front.index')->with('err', __('You are not Authorize'));
                    }
                } else {
                    return redirect()->route('front.index')->with('err', __('Rider Doesnt exits'));
                }
            }
            return view('front.dashboard.rider.edit', [
                'cities' => $cities,
                'rider' => $rider,
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['err' => $e->getMessage()]);
        }
    }

    public function update(UserRequest $request, $id)
    {
        try {
          
            $request->merge(['user_id' => $id]);
            $riderDto = UserRegisterDto::fromRequest($request);

            $this->userRepository->save($riderDto);
            if ($id == 0) {
                return redirect(route('front.dashboard.riders'))->with('status', 'Rider added successfully.');
            }
            return redirect(route('front.dashboard.riders'))->with('status', 'Rider updated successfully.');
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()]);
        }
    }


    public function destroy($id)
    {
        $this->userRepository->destroy($id);
        return response(['msg' => 'Rider is Deleted Successfully.']);
    }
}
