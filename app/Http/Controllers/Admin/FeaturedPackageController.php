<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Dtos\SubscriptionUpdateDto;
use App\Http\Libraries\DataTable;
use App\Http\Repositories\SubscriptionPackageRepository;
use App\Http\Requests\FromValidation;
use App\Http\Requests\SubscriptionPackageRequest;
use App\Models\SubscriptionPackage;

class FeaturedPackageController extends Controller
{

    protected SubscriptionPackageRepository $subscriptionPackageRepository;

    public function __construct(SubscriptionPackageRepository $subscriptionPackageRepository)
    {
        parent::__construct('adminData', 'admin');
        $this->breadcrumbTitle = 'Featured Packages';
        $this->breadcrumbs[route('admin.dashboard.index')] = ['icon' => 'fa fa-fw fa-home', 'title' => 'Dashboard'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => 'Manage Featured Packages'];
        $this->subscriptionPackageRepository = $subscriptionPackageRepository;
    }

    public function index()
    {
        $this->breadcrumbTitle = 'Featured Packages';
        return view('admin.featured.index');
    }

    public function all()
    {
        $columns = [
            ['db' => 'id', 'dt' => 'id'],
            ['db' => 'name', 'dt' => 'name'],
            ['db' => 'duration', 'dt' => 'duration'],
            ['db' => 'duration_type', 'dt' => 'duration_type'],
            ['db' => 'subscription_type', 'dt' => 'subscription_type'],
            ['db' => 'is_free', 'dt' => 'is_free'],
            ['db' => 'price', 'dt' => 'price'],
            ['db' => 'description', 'dt' => 'description'],
        ];
        $packages = $this->subscriptionPackageRepository->adminDataTable($columns);

        return response($packages);
    }

    public function edit($id)
    {
        $heading = (($id > 0) ? 'Edit Featured Package' : 'Add Featured Package');
        $this->breadcrumbs[route('admin.dashboard.featured-subscription.index')] = ['icon' => 'fa fa-fw fa-building', 'title' => 'Featured Packages'];
        $this->breadcrumbs['javascript:{};'] = ['icon' => 'fa fa-fw fa-money', 'title' => $heading];
        return view('admin.featured.edit', [
            'method' => 'PUT',
            'packageId' => $id,
            'action' => route('admin.dashboard.featured-subscription.update', $id),
            'heading' => $heading,
            'id' => $id,
            'package' => $this->subscriptionPackageRepository->get($id)
        ]);
    }

    public function update(SubscriptionPackageRequest $request, $id)
    {
        try {
            $subscriptionDto = SubscriptionUpdateDto::fromRequest($request);
            $this->subscriptionPackageRepository->save($subscriptionDto);
            if ($id == 0) {
                return redirect(route('admin.dashboard.featured-subscription.index'))->with('status', 'Package added successfully.');
            } else {
                return redirect(route('admin.dashboard.featured-subscription.index'))->with('status', 'Package updated successfully.');
            }
        }catch (\Exception $e){
            return redirect()->back()->with('err', $e->getMessage())->withInput();
        }

    }

    public function destroy($id)
    {
        try {
            $this->subscriptionPackageRepository->destroy($id);
            return response(['msg' => 'Deleted successfully']);
        } catch (\Exception $e) {
            return response(['err' => $e->getMessage()],400);
        }
    }
}
