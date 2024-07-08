<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Repositories\CityRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;


class CitiesController extends Controller
{

    protected $cityRepository;

    public function __construct(CityRepository $cityRepository)
    {
        parent::__construct();
        $this->cityRepository = $cityRepository;
    }

    public function cities(Request $request){
        $parent_id = $request->filled('parent_id')?$request->get('parent_id'):null;
        $this->cityRepository->setPaginate(0);
        $this->cityRepository->setRelations(['areas:id,parent_id,name,latitude,longitude,polygon']);
        $this->cityRepository->setSelect([
            'id',
            'parent_id',
            'name'
        ]);
        $cities =  $this->cityRepository->all(true);
        return responseBuilder()->success(__('Cities'), ['cities' => $cities]);
    }
}
