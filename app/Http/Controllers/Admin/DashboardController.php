<?php

namespace App\Http\Controllers\Admin;

use App\Http\Dtos\AdminDto;
use App\Http\Libraries\Uploader;
use App\Http\Repositories\AdminRepository;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use Exception;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected object $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
        parent::__construct('adminData', 'admin');
        $this->breadcrumbTitle = 'Admin';
    }

    public function index()
    {
        $this->breadcrumbTitle = 'Dashboard';
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Dashboard '];
        return view('admin.dashboard');
    }

    public function uploadImage(ImageRequest $request){
        $imageUploadedPath = '';
        if ($request->hasFile('image')) {
            $uploader = new Uploader('image');
            if ($uploader->isValidFile()) {

                $uploader->upload('admin', $uploader->fileName);
                if ($uploader->isUploaded()) {
                    $imageUploadedPath = $uploader->getUploadedPath();

                }
            }
            if (!$uploader->isUploaded()) {
                return 0;
            }
        }

        return response(['status_code' => '200', 'message' => 'Image uploaded successfully.', 'data' => $imageUploadedPath]);
    }

    public function multiImageUpload(ImageRequest $request)
    {
        try {
            $imageArray = [];
            $path = 'front/media';
            if ($request->has('path')) {
                $path = $request->path;
            }
            if ($request->hasFile('images')) {
                $data = $request->allFiles();
                foreach ($data['images'] as $key => $img) {
                    $image = uploadImage($img, $path);
                    $imageArray[$key] = $image;
                }
            }
            return responseBuilder()->success(__('Images Uploaded'), $imageArray);
        }catch (Exception $e){
            return responseBuilder()->error($e->getMessage());
        }
    }


    public function saveImage(Request $request) {
        $imageData = ['file' => ''];
        $imageResponse = $this->handleImage($request, $imageData, 'cms_pages', 'file');
        if ($imageResponse['status'] <= 0) {
            return response(['location' => ''])->setStatusCode(400, $imageResponse['message']);
        }
        return response(['location' => url($imageData['file'])]);
    }

    public function handleImage($request, &$data, $directory, $inputName = 'image') {

        $response = ['status' => 0, 'message' => __('Please upload a file')];

        if ($request->hasFile($inputName)) {

            $uploader = new Uploader($inputName);

            if ($uploader->isValidFiles()) {

                $uploader->upload($directory, $uploader->fileName);

                if ($uploader->isUploaded()) {

                    $data[$inputName] = $uploader->getUploadedPath();

                    $response['status'] = 1;

                    $response['message'] = 'Uploaded';

                    $response['mime_type'] = $uploader->getMimeType();

                    $response['path'] = $uploader->getUploadedPath();

                }

            }

            if (!$uploader->isUploaded()) {

                $response = ['message' => __($uploader->getMessage()), 'status' => -1];

            }

        }

        return $response;

    }



}
