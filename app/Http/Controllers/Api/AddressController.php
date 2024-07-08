<?php

namespace App\Http\Controllers\Api;

use App\Http\Repositories\AddressRepository;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{

    public AddressRepository $addressRepository;

    public function __construct()
    {
        $this->addressRepository = new AddressRepository();
    }

    public function index(Request $request)
    {
        $this->addressRepository->setRelations([
            'city:id,name',
            'area:id,name',
        ]);
        $data = $this->addressRepository->all_for_listing($request);
        return responseBuilder()->success(__('Address Data'), $data);
    }

    public function create()
    {
    }

    public function makeDefault(Request $request)
    {
        $this->addressRepository->makeDefault($request);
        return responseBuilder()->success(__('Address set default successfully.'));
    }



    public function store(AddressRequest $request, $id)
    {
        try {
        $this->addressRepository->getUser();
        $this->addressRepository->save($request, $id);

        if ($id > 0) {
            return responseBuilder()->success(__('Address is Updated'));
        } else {
            return responseBuilder()->success(__('Address is Created'));
        }

        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }

    }

    public function show($id)
    {
    }

    public function edit(Request $request)
    {
        $data = $this->addressRepository->get($request->id);
        return responseBuilder()->success(__('Get Address Data'), $data->toArray());
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        try {
            if (!empty($id)) {
                $result = $this->addressRepository->delete($id);

            }
            return responseBuilder()->success(__('Address is Deleted.'));

        } catch (\Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }
}
