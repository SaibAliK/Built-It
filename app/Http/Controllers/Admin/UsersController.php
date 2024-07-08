<?php

namespace App\Http\Controllers\Admin;
use App\Http\Dtos\UserRegisterDto;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;

class UsersController extends Controller {

    protected $userRepository;

    public function __construct(UserRepository $userRepository) {
        parent::__construct('adminData', 'admin');
        $this->userRepository = $userRepository;
        $this->userRepository->setFromWeb(true);
        $this->breadcrumbTitle = 'Users';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home','title' => 'Dashboard'];
    }

    public function index() {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-user', 'title' => 'Manage Users'];
        return view('admin.users.index');
    }

    public function all() {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'user_name', 'dt' => 'user_name'],
            ['db' => 'email', 'dt' => 'email'],
            ['db' => 'is_active', 'dt' => 'is_active'],
            ['db' => 'is_verified', 'dt' => 'is_verified'],
        ];
        $users = $this->userRepository->adminDataTable($columns);
        return response($users);
    }

    public function edit($id) {

        $heading = (($id > 0) ? 'Edit User' : 'Add User');
        $this->breadcrumbs[route('admin.dashboard.users.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Manage Users'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        $user = $this->userRepository->get($id);
        if (is_null($user)){
            return redirect(route('admin.dashboard.users.index'))->with('err', 'The selected user no longer exists.');
        }
        return view('admin.users.edit', [
            'method' => 'PUT',
            'storeId' => $id,
            'action' => route('admin.dashboard.users.update', $id),
            'heading' => $heading,
            'user' => $user
        ]);
    }

    public function update(UserRequest $request,$id)
    {
        try {
            $user = UserRegisterDto::fromRequest($request);
//            dd($user);
            $this->userRepository->save($user);
            if($id==0){
                return redirect(route('admin.dashboard.users.index'))->with('status', 'User Added Successfully');
            }
            else{
                return redirect(route('admin.dashboard.users.index'))->with('status', 'User Updated Successfully');
            }

        }catch (\Exception $e){
            return redirect()->back()->withInput()->with('err', $e->getMessage());
        }

    }
    public function destroy($id) {
        try {
            $this->userRepository->destroy($id);
            return response(['msg' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()],400);
        }
    }

}
