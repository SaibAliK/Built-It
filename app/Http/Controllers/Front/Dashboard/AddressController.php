<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Repositories\AddressRepository;
use App\Http\Repositories\CityRepository;
use App\Http\Requests\AddressRequest;
use Illuminate\Http\Request;
use Exception;
use App\Http\Controllers\Controller;

class AddressController extends Controller
{
    public AddressRepository $addressRepository;
    protected CityRepository $cityRepository;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs[0] = ['url' => route('front.dashboard.index'), 'title' => __('Home')];
        $this->addressRepository = new AddressRepository();
        $this->cityRepository = new CityRepository();
    }

    public function index(Request $request)
    {
        $this->breadcrumbTitle = __('Manage Addresses');
        $this->breadcrumbs[1] = ['url' => '', 'title' => __('Manage Addresses')];
        $this->addressRepository->setPaginate(4);
        $data = $this->addressRepository->all_for_listing($request);
        return view('front.dashboard.address.index', get_defined_vars());
    }

    public function create(Request $request, $id)
    {
        $data = $this->addressRepository->getModel();
        $cities = $this->cityRepository->all(true);
        $areas= $this->cityRepository->getAreas($id,0);
        $area_id = 0;
        $store_id = 0;
        $this->breadcrumbs[route('front.dashboard.address.index')] = ['title' => __('Manage Address')];
        $this->breadcrumbTitle = __('Add new address');
        $this->breadcrumbs[1] = ['url' => '', 'title' => __('Add new address')];
        return view('front.dashboard.address.create', get_defined_vars());
    }

    function area($id){
        $area= $this->cityRepository->get($id);
        return $area;
    }

    public function store(AddressRequest $request, $id)
    {
        try {
            if ($request->latitude == "" && $request->longitude == "") {
                // return redirect()->back()->with('err', 'Please add proper address.');
            } else {
                $this->addressRepository->save($request, $id);
            }

            if ($id != '0') {
                return redirect()->route('front.dashboard.address.index')->with('status', __('Address is Updated.'));
            }
            return redirect()->route('front.dashboard.address.index')->with('status', __('Address is Created.'));

        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function storeWithoutView(AddressRequest $request, $id)
    {
      
        try {
            if ($request->latitude == "" && $request->longitude == "") {
                // return redirect()->back()->with('err', 'Please add proper address.');
            } else {
                $this->addressRepository->save($request, $id);
            }


            if ($id > 0) {
                return redirect()->back()->with('status', __('Address is Updated.'));
            }
            return redirect()->back()->with('status', __('Address is Created.'));

        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function show($id)
    {

    }

    public function edit(Request $request)
    {
        $this->breadcrumbs[route('front.dashboard.address.index')] = ['title' => __('Manage Address')];
        $cities = $this->cityRepository->all(true);
        $this->breadcrumbTitle = __('Edit Address');
        $this->breadcrumbs[1] = ['url' => '', 'title' => __('Edit Address')];
        $data = $this->addressRepository->get($request->id);
        $store_id = 0;
        $areas= $this->cityRepository->getAreas($data->city_id,0);
        $id = $request->id;
        if(isset($data->area_id))
        {
            $area_id = $data->area_id;
        }
        else{
            $area_id = 0;
        }

        return view('front.dashboard.address.create', get_defined_vars());
    }

    public function editWithoutView(Request $request)
    {
        return $this->addressRepository->get($request->id);
    }

    public function update(Request $request, $id)
    {
//        dd($request->all(),$id);
    }

    public function makeDefault(Request $request)
    {

        $this->addressRepository->makeDefault($request);
        return redirect()->back()->with('status', __('Address set default successfully.'));
    }


    public function destroy($id)
    {
        if (!empty($id)) {
            $result = $this->addressRepository->delete($id);
            if (!empty($result)) {
                return redirect()->back()->with('status', __('Address is Deleted.'));
            }
        }

    }
}
