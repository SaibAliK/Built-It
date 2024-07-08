<?php

use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\ResetPasswordController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AreasController;
use App\Http\Controllers\Admin\RidersController;
use App\Http\Controllers\Admin\WithdrawsController;
use App\Http\Controllers\Admin\SuppliersController;
use App\Http\Controllers\Admin\GalleriesController;
use App\Http\Controllers\Admin\FaqsController;
use App\Http\Controllers\Admin\ArticlesController;
use App\Http\Controllers\Admin\DeliveryCompany;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\OffersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\SiteSettingsController;
use App\Http\Controllers\Admin\FeaturedPackageController;
use App\Http\Controllers\Admin\AdministratorsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SubCategoriesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::as('admin.')->group(function () {

    Route::group(['as' => 'auth.', 'namespace' => 'Auth'], function () {
        Route::get('/', [LoginController::class, 'showLoginForm'])->name('login.show-login-form');
        Route::post('login', [LoginController::class, 'attemptLogin'])->name('login');
        Route::post('logout', [LoginController::class, 'logout'])->name('logout.post');
        Route::get('logout', [LoginController::class, 'logout'])->name('logout.get');
        Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot-password.send-reset-link-email');
        Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('forgot-password.show-link-request-form');
        Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('forgot-password.show-reset-form');
        Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('forgot-password.reset');
    });

    Route::middleware('auth:admin')->as('dashboard.')->group(function () {
        Route::resources([
            'cities' => CityController::class,
            'product' => ProductsController::class,
            'cities.areas' => AreasController::class,
            'users' => UsersController::class,
            'suppliers' => SuppliersController::class,
            'delivery-companies' => DeliveryCompany::class,
            'faqs' => FaqsController::class,
            'withdraws' => WithdrawsController::class,
            'riders'             => RidersController::class,
            'galleries' => GalleriesController::class,
            'articles' => ArticlesController::class,
            'subscriptions' => SubscriptionController::class,
            'pages' => PagesController::class,
            'products' => ProductsController::class,
            'site-settings' => SiteSettingsController::class,
            'featured-subscription' => FeaturedPackageController::class,
            'administrators' => AdministratorsController::class,
        ]);


        Route::prefix('list/')->group(function () {
            Route::post('withdraws/{store_id?}', [WithdrawsController::class, 'all'])->name('withdraws.ajax.list');
            Route::post('product', [ProductsController::class, 'all'])->name('products.ajax.list');
            Route::post('users', [UsersController::class, 'all'])->name('users.ajax.list');
            Route::post('riders', [RidersController::class, 'all'])->name('riders.ajax.list');
            Route::post('articles', [ArticlesController::class, 'all'])->name('articles.ajax.list');
            Route::post('subscription_packages', [SubscriptionController::class, 'all'])->name('subscription_packages.ajax.list');
            Route::post('featured_packages', [FeaturedPackageController::class, 'all'])->name('featured_packages.ajax.list');
            Route::post('pages', [PagesController::class, 'all'])->name('pages.ajax.list');
            Route::post('suppliers', [SuppliersController::class, 'all'])->name('suppliers.ajax.list');
            Route::post('product', [ProductsController::class, 'all'])->name('products.ajax.list');
            Route::post('companies', [DeliveryCompany::class, 'all'])->name('companies.ajax.list');
            Route::post('site-settings', [SiteSettingsController::class, 'all'])->name('site-settings.ajax.list');
            Route::post('faqs', [FaqsController::class, 'all'])->name('faqs.ajax.list');
            Route::post('administrators', [AdministratorsController::class, 'all'])->name('administrators.ajax.list');
        });


        Route::get('withdraws/{withdraws}/pay-with-paypal', [WithdrawsController::class, 'payWithPayPal'])->name('withdraws.pay-with-paypal');
        Route::get('withdraws/{withdraws}/paypal-payment-processed', [WithdrawsController::class, 'paypalPaymentProcessed'])->name('withdraws.paypal-payment-processed');
        Route::get('withdraws/paypal-payment-canceled', [WithdrawsController::class, 'paypalPaymentCanceled'])->name('withdraws.paypal-payment-canceled');


        Route::get('offers', [OffersController::class, 'index'])->name('offers.index');
        Route::post('list/offers', [OffersController::class, 'all'])->name('offers.ajax.list');
        Route::post('list/offer', [OffersController::class, 'all'])->name('offers.ajax.list');
        Route::get('offer-status', [OffersController::class, 'changeStatus'])->name('offers.change.status');


        Route::get('home', [DashboardController::class, 'index'])->name('index');
        Route::get('edit-profile', [ProfileController::class, 'editProfile'])->name('edit-profile');
        Route::put('update-profile', [ProfileController::class, 'updateProfile'])->name('update-profile');
        Route::post('save-image', [DashboardController::class, 'saveImage'])->name('save-image');
        Route::get('id-card-verify/{id}', [SuppliersController::class, 'idCardVerify'])->name('id.card.verify');
        Route::get('site-settings/table/values', [SiteSettingsController::class, 'tableValues'])->name('site-settings.table.values');
        Route::put('toggle-status/administrators/{id}', [AdministratorsController::class, 'toggleStatus'])->name('administrators.toggle-status');

        //category
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('categories/edit/{id}', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/update/{id}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/delete/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::post('list/categories', [CategoryController::class, 'all'])->name('categories.ajax.list');


        //subcategory
        Route::get('/subcategories', [SubCategoriesController::class, 'index'])->name('categories.sub-categories.index');
        Route::get('subcategories/edit/{parentId}/{id}', [SubCategoriesController::class, 'edit'])->name('categories.sub-categories.edit');
        Route::put('subcategories/update/{parentId}/{id}', [SubCategoriesController::class, 'update'])->name('categories.sub-categories.update');
        Route::post('list/subcategories', [SubCategoriesController::class, 'all'])->name('categories.sub-categories.ajax.list');

    });

    Route::post('upload-image', [DashboardController::class, 'uploadImage'])->name('upload-image');
    Route::post('upload-multi-image', [DashboardController::class, 'multiImageUpload'])->name('upload-image.multi');

});



