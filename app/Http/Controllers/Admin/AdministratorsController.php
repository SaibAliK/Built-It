<?php

namespace App\Http\Controllers\Admin;

use App\Http\Dtos\AdminDto;
use App\Http\Repositories\AdminRepository;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Http\Libraries\DataTable;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Exception;

class AdministratorsController extends Controller
{

    protected object $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        parent::__construct('adminData', 'admin');
        $this->adminRepository = $adminRepository;
        $this->breadcrumbTitle = 'Administrators';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
    }


    public function index()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-user', 'title' => 'Manage Administrators'];
        return view('admin.administrators.index');
    }

    public function all()
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'email', 'dt' => 'email'],
            ['db' => 'is_active', 'dt' => 'is_active'],
            ['db' => 'created_at', 'dt' => 'created_at'],
            ['db' => 'updated_at', 'dt' => 'updated_at'],
            ['db' => 'deleted_at', 'dt' => 'deleted_at'],
        ];
        DataTable::init(new Admin, $columns);
        DataTable::where('id', '!=', 1);

        $fullName = \request('datatable.query.full_name', '');
        $userName = \request('datatable.query.name', '');
        $email = \request('datatable.query.email', '');
        $trashedAdmins = \request('datatable.query.trashedAdmins', NULL);
        $activeAdmins = \request('datatable.query.activeAdmins', '');
        $createdAt = \request('datatable.query.createdAt', '');
        $updatedAt = \request('datatable.query.updatedAt', '');
        $deletedAt = \request('datatable.query.deletedAt', '');
        if (!empty($trashedAdmins)) {
            DataTable::getOnlyTrashed();
        }

        if ($createdAt != '') {
            $createdAt = Carbon::createFromFormat('m/d/Y', $createdAt)->format('Y-m-d');
            DataTable::where('created_at', 'LIKE', '%' . addslashes($createdAt) . '%');
        }

        if ($updatedAt != '') {
            $updatedAt = Carbon::createFromFormat('m/d/Y', $updatedAt)->format('Y-m-d');
            DataTable::where('updated_at', 'LIKE', '%' . addslashes($updatedAt) . '%');
        }

        if (!empty($deletedAt)) {
            $where = function ($query) use ($deletedAt) {
                $deletedAt = Carbon::createFromFormat('m/d/Y', $deletedAt)->format('Y-m-d');
                $query->where('deleted_at', 'LIKE', '%' . addslashes($deletedAt) . '%');

            };
            DataTable::getOnlyTrashed($where);
        }

        if ($fullName != '') {
            DataTable::where('full_name', 'LIKE', '%' . addslashes($fullName) . '%');
        }

        if ($userName != '') {
            DataTable::where('name', 'LIKE', '%' . addslashes($userName) . '%');
        }

        if ($email != '') {
            DataTable::where('email', 'like', '%' . addslashes($email) . '%');
        }

        if ($activeAdmins != '') {
            DataTable::where('is_active', '=', $activeAdmins);
        }

        if (!empty($sortOrder) && !empty($sortColumn)) {
            DataTable::orderBy($sortColumn, $sortOrder);
        }

        $administrators = DataTable::get();
        if (sizeof($administrators['data']) > 0) {
            $dateFormat = config('settings.date-format');
            foreach ($administrators['data'] as $key => $admin) {
                $administrators['data'][$key]['is_active'] = '<a href="javascript:{};" data-url="' . route('admin.dashboard.administrators.toggle-status', ['id' => $admin['id']]) . '" class="toggle-status-button">';
                $administrators['data'][$key]['is_active'] .= (($admin['is_active'] == 1) ? 'Yes' : 'No') . '</a>';

                if (!empty($trashedAdmins)) {
                    $administrators['data'][$key]['actions'] = '<a class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill restore-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.administrators.restore', ['administrators' => $admin['id']]) . '" title="Restore Admin"><i class="fa fa-fw fa-undo"></i></a>' .
                        '<span class="m-badge m-badge--danger">' . Carbon::createFromFormat('Y-m-d H:i:s', $admin['deleted_at'])->format($dateFormat) . '</span>';
                } else {
                    $administrators['data'][$key]['actions'] = '<a href="' . route('admin.dashboard.administrators.edit', $admin['id']) . '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit"><i class="la la-edit"></i></a>' .
                        '<a class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill delete-record-button" href="javascript:{};" data-url="' . route('admin.dashboard.administrators.destroy', $admin['id']) . '" title="Delete"><i class="la la-trash"></i></a>';
                }
                unset($administrators['data'][$key]['role']);
            }
        }
        return response($administrators);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-user', 'title' => 'Add Administrator'];
        return view('admin.administrators.create', [
            'selected_admin' => new Admin(),
            'action' => route('admin.dashboard.administrators.store')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(AdminRequest $request)
    {
        try {
            $adminDto = AdminDto::fromRequest($request);
            $admin = $this->adminRepository->save($adminDto);
            return redirect(route('admin.dashboard.administrators.index'))->with('status', 'New administrator added successfully');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());

        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-user', 'title' => 'Edit Administrator'];
        return view('admin.administrators.edit', [
            'selected_admin' => $admin,
            'action' => route('admin.dashboard.administrators.update', $id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(AdminRequest $request, $id)
    {
        try {
            $request->merge(['id' => $id]);
            $adminDto = AdminDto::fromRequest($request);
            $admin = $this->adminRepository->save($adminDto);
            return redirect(route('admin.dashboard.administrators.index'))->with('status', 'Account updated successfully');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors($e->getMessage());

        }
    }

    public function toggleStatus($id)
    {
        $admin = Admin::find($id);
        $response = ['msg' => 'Account does not exist'];
        if ($admin !== null) {
            if ($admin->is_active == 1) {
                $admin->is_active = 0;
            } else {
                $admin->is_active = 1;
            }
            $admin->save();
            $response['msg'] = 'Account updated';
        }
        return response($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Admin::destroy($id);
        return response(['msg' => 'Account deleted']);
    }

    public function restore($id)
    {
        Admin::withTrashed()->where('id', $id)->update(['deleted_at' => NULL]);
        return response(['msg' => 'Admin restored successfully.']);
    }

    public function bulkDelete($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $key => $value) {
            Admin::destroy($value);
        }
        return response(['msg' => 'Admins deleted successfully.']);
    }

    public function bulkRestore($ids)
    {
        $ids = explode(',', $ids);
        foreach ($ids as $key => $value) {
            Admin::withTrashed()->where('id', $value)->update(['deleted_at' => NULL]);
        }
        return response(['msg' => 'Admins restored successfully.']);
    }

}
