<?php

namespace App\Http\Controllers\Front\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ColorRepository;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    protected ColorRepository $colorRepository;

    public function __construct()
    {
        parent::__construct();
        $this->breadcrumbs[0] = ['url' => route('front.dashboard.index'), 'title' => __('Home')];
        $this->colorRepository = new ColorRepository();
    }

    public function index(Request $request)
    {
        $this->breadcrumbTitle = __('Manage Colors');
        $this->breadcrumbs[1] = ['url' => '', 'title' => __('Manage Colors')];
        $user_id = auth()->user()->id;
        if (auth()->user()->color()->first()) {
            $colors = auth()->user()->color()->first();
        } else {
            $colors = $this->colorRepository->getModel();
        }

        return view('front.dashboard.color.add_edit', ['color' => $colors]);
    }

    public function save(Request $request)
    {
        try {

            $request->validate([
                'store_id' => 'required',
            ]);

            $color = Color::where('store_id', $request->store_id)->first();
            if ($color) {
                $color->update([
                    'heading_color' => $request->heading_color,
                    'text_color' => $request->text_color,
                    'icons_color' => $request->icons_color,
                    'background_color' => $request->background_color,
                ]);
            } else {
                $colors = Color::create($request->all());
            }
            return redirect()->route('front.dashboard.color.index')->with('status', __('Color Saved Successfully'));

        } catch (\Exception $e) {
            return redirect()->back()->with('err', $e->getMessage());
        }
    }
}
