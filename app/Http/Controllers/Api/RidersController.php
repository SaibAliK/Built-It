<?php

namespace App\Http\Controllers\Api;

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
    protected object $cityRepository;
    protected object $categoryRepository, $userRepository;

    public function __construct(CityRepository $cityRepository, UserRepository $userRepository)
    {
        parent::__construct();
        $this->userRepository = $userRepository;
        $this->cityRepository = $cityRepository;
    }

    public function index()
    {
        $user = auth()->user()->isCompany();
        if ($user) {
            $this->userRepository->setSelect([
                'id', 'company_id', 'city_id', 'email', 'phone', 'image', 'id_card_images', 'address', 'latitude', 'longitude', 'supplier_name'
            ]);

            $this->userRepository->setPaginate(10);
            $riders = $this->userRepository->riders(auth()->user()->id);

            if ($riders) {
                return responseBuilder()->success(__('Riders'), $riders);
            } else {
                return responseBuilder()->success(__('Riders Not Found'));
            }
        } else {
            return responseBuilder()->success(__('Only Delivery Company can access riders'));
        }
    }

    public function edit($id)
    {
        $this->userRepository->setRelations(['city']);
        $rider = $this->userRepository->get($id);
        return responseBuilder()->success(__('Rider'), [
            'rider' => $rider,
            'id' => $id
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'supplier_name' => 'required|array',
            'supplier_name.en' => 'required',
            'id_card_images' => 'required|array',
            'id_card_images.*' => 'required|string',
            'image' => 'required',
        ]);

        if ($id == 0) {
            $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
            ]);
        }

        $user = auth()->user()->isCompany();

        if ($user) {
            try {
                $request->merge(['user_id' => $id]);
                $request->merge(['parent_id' => auth()->user()->id]);
                $riderDto = UserRegisterDto::fromRequest($request);

                $rider = $this->userRepository->save($riderDto);

                if ($id == 0) {
                    return responseBuilder()->success(__('Rider Register Successfully'), ['rider' => $rider]);
                }
                return responseBuilder()->success(__('Rider Updated Successfully'), ['rider' => $rider]);
            } catch (\Exception $e) {
                return responseBuilder()->error($e->getMessage());
            }
        } else {
            return responseBuilder()->error('Only Delivery Company can access riders');
        }
    }

    public function destroy($id)
    {
        try {
            $rider = $this->userRepository->destroy($id);
            return responseBuilder()->success(__('Rider deleted Successfully'));
        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }
}
