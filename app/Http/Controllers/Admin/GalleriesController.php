<?php namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use App\Http\Requests\GalleryRequest;
use App\Http\Libraries\Uploader;
use App\Http\Libraries\DataTable;
use App\Http\Controllers\Controller;
use Request;

class GalleriesController extends Controller
{
    public function __construct()
    {
        parent::__construct('adminData', 'admin');
        $this->breadcrumbTitle = 'Galleries';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }

    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Gallery'];
        $images = Gallery::all();
        return view('admin.galleries.index', ['images' => $images]);
    }

    public function destroy($imageId)
    {
        Gallery::destroy($imageId);
        return response(['msg' => 'Image deleted successfully.']);
    }

    public function store(GalleryRequest $request)
    {
        $carImages = [];
        $data = $request->except('_token');
        $notImage = false;
        $uploader = new Uploader();
        if (isset($data['images']) && count($data['images']) > 0) {
            foreach ($data['images'] as $key => $img) {
                if (is_object($img)) {
                    $extension = $img->getClientMimeType();
                    if (str_contains($extension, 'image')) {
                        $uploader->setFile($img);
                        if ($uploader->isValidFile()) {
                            $uploader->upload('sponsors', $uploader->fileName);
                            if ($uploader->isUploaded()) {
                                $carImages[] = $uploader->getUploadedPath();
                            }
                        }
                    } else {
                        $notImage = true;
                    }
                }
            }
        } else {
            return redirect()->back()->with('err', 'No image selected');
        }
        foreach ($carImages as $key => $image) {
            Gallery::create(['image' => $image]);
        }
        if ($notImage) {
            return redirect()->back()->with('err', 'One of the files did not upload because it was not an image.');
        }
        return redirect()->back()->with('status', 'Images Added successfully.');
    }
}



