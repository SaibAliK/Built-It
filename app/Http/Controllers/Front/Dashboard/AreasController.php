<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Repositories\CityRepository;
use App\Http\Requests\AreasRequest;
use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AreasController extends Controller
{
    protected CityRepository $cityRepository;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs[route('front.dashboard.index')] = ['title' => __('Home')];
        $this->cityRepository = new CityRepository();
    }

    public function index()
    {
        $this->breadcrumbTitle = __('Delivery Areas');
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => __('Delivery Areas')];
        $data = $this->cityRepository->getSavedArea();
        return view('front.dashboard.areas.index', ['data' => $data]);
    }

    public function create()
    {
        $this->cityRepository->getUser();
        $user = auth()->user();
        $this->cityRepository->setRelations(['areas']);
        $cities = $this->cityRepository->all();
        $this->breadcrumbTitle = __('add Delivery Area');
        $this->breadcrumbs['javascript:{};'] = ['title' => __('add Delivery Area')];
        return view('front.dashboard.areas.create', get_defined_vars());
    }

    public function store(AreasRequest $request)
    {
        try {
            $data = $this->cityRepository->saveDArea($request);
            if ($request->has('price')) {
                return redirect()->route('front.dashboard.areas.index')->with('status', __('Area is Created.'));
            } else {
                return redirect(route('front.subscription-packages'))->with('status', __('Area is Created.'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }

    public function show($id)
    {

    }

    public function edit(Request $request, $id)
    {
        $this->breadcrumbTitle = __('Edit Delivery Area');
        $this->breadcrumbs['javascript:{};'] = ['title' => __('Edit Delivery Area')];
        $user = auth()->user();
        $this->cityRepository->setRelations(['areas']);
        $cities = $this->cityRepository->all();
        $data = $this->cityRepository->getArea($id);
        return view('front.dashboard.areas.create', get_defined_vars());
    }

    public function update(Request $request, $id)
    {
        // dd($request->all(),$id);
    }

    public function destroy($id)
    {
        try {
            if (!empty($id)) {
                $result = $this->cityRepository->deleteAreas($id);
                if (!empty($result)) {
                    return redirect()->back()->with('status', __('Area is Deleted.'));
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }
}
