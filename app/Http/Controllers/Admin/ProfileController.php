<?php

namespace App\Http\Controllers\Admin;

use App\Http\Dtos\AdminDto;
use App\Http\Libraries\Uploader;
use App\Http\Repositories\AdminRepository;
use App\Http\Requests\AdminRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use Exception;

class ProfileController extends Controller
{
    protected object $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
        parent::__construct('adminData', 'admin');
        $this->breadcrumbTitle = 'Admin';
    }


    public function editProfile()
    {
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Edit Admin '];
        return view('admin.profile.edit_profile',['languageId'=> 1]);
    }

    public function updateProfile(AdminRequest $request)
    {
        try {
            $adminDto = AdminDto::fromRequest($request);
            $admin = $this->adminRepository->save($adminDto);
            $admin->token = session('ADMIN_DATA')['token'];
            session()->put('ADMIN_DATA', $admin->toArray());
            return redirect(route('admin.dashboard.edit-profile'))->with('status', 'profile update successfully');
        }catch (Exception $e){
            return back()->withErrors($e->getMessage());

        }
    }



}
