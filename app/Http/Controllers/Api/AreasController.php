<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CityRepository;
use App\Http\Requests\AreasRequest;
use Exception;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class AreasController extends Controller
{
    protected CityRepository $cityRepository;

    public function __construct()
    {
        parent::__construct();
        $this->cityRepository = new CityRepository();
    }

    public function index(Request $request)
    {
        $data = $this->cityRepository->getSavedAreaForApi($request->all());
        return responseBuilder()->success(__('Areas Data'), $data->toArray());
    }

    public function create()
    {
        dd("d");
    }

    public function store(AreasRequest $request)
    {
        try {
            $data = $this->cityRepository->saveDArea($request);
            if ($request->get('id') == 0) {
                return responseBuilder()->success(__('Area is Created.'));
            } else {
                return responseBuilder()->success(__('Area is Updated.'));
            }
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }

    public function show($id)
    {
    }

    public function edit(Request $request, $id)
    {

        try {
            $data = $this->cityRepository->getArea($id);
            if (isNull($data)) {
                throw_if(!$data, __('No Record Found'));
            }
            return responseBuilder()->success(__('Get Area Data'), $data->toArray());
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
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
                if ($result) {
                    return responseBuilder()->success(__('Area delete successfully.'));
                }
            }
        } catch (Exception $e) {
            return responseBuilder()->error($e->getMessage());
        }
    }
}
