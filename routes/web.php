<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Front\IndexController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\CheckOutController;
use App\Http\Controllers\Front\Auth\LoginController;
use App\Http\Controllers\Front\Auth\RegisterController;
use App\Http\Controllers\Front\Dashboard\RidersController;
use App\Http\Controllers\Front\Dashboard\WithDrawController;
use App\Http\Controllers\Front\Dashboard\ReviewController;
use App\Http\Controllers\Front\Dashboard\ColorController;
use App\Http\Controllers\Front\Dashboard\AreasController;
use App\Http\Controllers\Front\Auth\SocialLoginController;
use App\Http\Controllers\Front\Auth\VerifyEmailController;
use App\Http\Controllers\Front\Dashboard\ProfileController;
use App\Http\Controllers\Front\Dashboard\NotificationController;
use App\Http\Controllers\Front\Auth\ResetPasswordController;

// use App\Http\Controllers\Front\Dashboard\RiderController;
use App\Http\Controllers\Front\Auth\ForgotPasswordController;
use App\Http\Controllers\Front\Dashboard\Store\ProductController;
use App\Http\Controllers\Front\Dashboard\SubscriptionController;
use App\Http\Controllers\Front\Dashboard\UserSubscriptionController;
use App\Http\Controllers\Front\Dashboard\AddressController;
use App\Http\Controllers\Front\Dashboard\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

//Route::get('/_debugbar/assets/stylesheets', [
//    'as' => 'debugbar-css',
//    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@css'
//]);
//
//Route::get('/_debugbar/assets/javascript', [
//    'as' => 'debugbar-js',
//    'uses' => '\Barryvdh\Debugbar\Controllers\AssetController@js'
//]);
//
//Route::get('/_debugbar/open', [
//    'as' => 'debugbar-open',
//    'uses' => '\Barryvdh\Debugbar\Controllers\OpenController@handler'
//]);

Route::namespace('Front')->as('front.')->group(function () {

    Route::get('/', [IndexController::class, 'index'])->name('index');
    Route::get('/404', [IndexController::class, 'error404'])->name('404');
    Route::get('/pages/{slug}', [IndexController::class, 'pages'])->name('pages');
    Route::post('contact-us', [IndexController::class, 'contactEmail'])->name('contactUs.email');
    Route::get('change-currency/{currency?}', [IndexController::class, 'change_currency'])->name('change_currency');
    Route::get('/subcategory/{id}', [IndexController::class, 'subcategory'])->name('subcategory');
    Route::get('articles', [IndexController::class, 'articles'])->name('articles');
    Route::get('article/{slug}', [IndexController::class, 'articleDetail'])->name('article.detail');
    Route::get('faqs', [IndexController::class, 'faqs'])->name('faqs');
    Route::get('gallery', [IndexController::class, 'gallery'])->name('gallery');
    Route::get('/categories', [IndexController::class, 'categories'])->name('categories');
    Route::get('/products', [IndexController::class, 'products'])->name('products');
    Route::get('/product-detail/{id}/{rev?}', [IndexController::class, 'productDetail'])->name('product.detail');
    Route::post('/email-subscribe', [IndexController::class, 'emailSubscribe'])->name('email.subscribe');
    Route::get('put_in_session', [IndexController::class, 'putSession'])->name('put_in_session');
    Route::get('supplier-map-search', [IndexController::class, 'supplierMapSearch'])->name('supplier_map_search');
    Route::get('/suppliers', [IndexController::class, 'suppliers'])->name('suppliers');
    Route::get('/supplier-detail/{id}/{rev?}', [IndexController::class, 'suppliersDetail'])->name('supplier.detail');
    Route::get('save-user-data', [IndexController::class, 'SaveUserData2'])->name('save.userData');

    Route::namespace('Auth')->as('auth.')->group(function () {
        route::get('/register', [RegisterController::class, 'showRegistrationPage'])->name('register');
        route::get('/register/{type}', [RegisterController::class, 'showRegistrationForm'])->name('register.foam');
        route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        route::post('/login', [LoginController::class, 'login'])->name('login.submit');
        route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
        route::get('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/redirect/{service}', [SocialLoginController::class, 'redirect'])->name('login.social');
        Route::get('/callback/{service}', [SocialLoginController::class, 'callback'])->name('login.social.callback');

        route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('forgot-password');
        route::post('/forgot-password-resend', [ForgotPasswordController::class, 'forgotPasswordResend'])->name('forgot-password.code.resend');
        route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgot-password.submit');

        route::get('password/reset', [ResetPasswordController::class, 'showResetForm'])->name('show.reset.form');
        route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.reset.submit');

        route::get('/verification', [VerifyEmailController::class, 'emailVerificationForm'])->name('verification');
        route::get('/verification-resend', [VerifyEmailController::class, 'emailVerificationResend'])->name('verification.code.resend');
        route::post('/verification', [VerifyEmailController::class, 'emailVerificationPost'])->name('verification.submit');
    });

    Route::namespace('Dashboard')->as('dashboard.')->prefix('dashboard')->middleware(['auth', 'email_verified'])->group(function () {

        Route::middleware(['check_subscription'])->group(function () {
            route::get('/', [ProfileController::class, 'index'])->name('index');
            route::get('/edit/profile', [ProfileController::class, 'edit'])->name('edit.profile');
            route::post('/update/profile', [ProfileController::class, 'update'])->name('update.profile');
            route::get('/edit/password', [ProfileController::class, 'editPassword'])->name('edit.password');
            route::post('/update/password', [ProfileController::class, 'updatePassword'])->name('update.password');
        });

        Route::middleware(['delivery_Company'])->group(function () {
            //riders
            route::get('/riders', [RidersController::class, 'index'])->name('riders');
            route::get('riders/{id}/edit', [RidersController::class, 'edit'])->name('edit.riders');
            route::post('update/riders/{id}', [RidersController::class, 'update'])->name('update.riders');
            route::get('riders/delete/{id}', [RidersController::class, 'destroy'])->name('destroy.riders');
        });

        Route::middleware(['user'])->group(function () {
            //Ads To Cart Routes
            Route::get('cart', [CartController::class, 'index'])->name('cart.index');
            route::post('add-to-cart', [CartController::class, 'save'])->name('cart.add');
            route::post('update-cart', [CartController::class, 'update'])->name('cart.update');
            route::get('delete-cart/{id}', [CartController::class, 'delete'])->name('cart.delete');

            //Review routes
            Route::post('reviews', [ReviewController::class, 'save'])->name('store.create-reviews');

            /*checkout controller*/
            route::get('check-out', [CheckOutController::class, 'index'])->name('checkout.index');
            route::post('check-outs', [CheckOutController::class, 'save'])->name('checkout.buy-now');

            route::get('address-index', [AddressController::class, 'index'])->name('address.index');
            route::get('address-create/{id}', [AddressController::class, 'create'])->name('address.create');
            route::post('add-address/{id}', [AddressController::class, 'store'])->name('address.store');
            route::post('add-address-withoutView/{id}', [AddressController::class, 'storeWithoutView'])->name('address.store.withoutView');
            route::post('make-default', [AddressController::class, 'makeDefault'])->name('address.make-default');
            route::get('delete-address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
            route::get('edit-address/{id}', [AddressController::class, 'edit'])->name('address.edit');
            route::get('edit-address-without-view/{id}', [AddressController::class, 'editWithoutView'])->name('address.edit.withoutView');
            route::get('area/{id}', [AddressController::class, 'area'])->name('area');
        });


        //order routes
        Route::get('orders/{status?}/{id?}', [OrderController::class, 'index'])->name('order.index');
        Route::get('order-detail/{id}', [OrderController::class, 'get'])->name('order.detail');
        Route::post('order-cancel', [OrderController::class, 'cancelled'])->name('order.cancel');
        Route::post('order-send', [OrderController::class, 'assign'])->name('order.send');
        Route::post('order-accept', [OrderController::class, 'accept'])->name('order.accept');
        Route::post('order-delivered', [OrderController::class, 'delivered'])->name('order.delivered');
        Route::post('order-complete', [OrderController::class, 'complete'])->name('order.complete');
        route::get('order/printPdf/{id}', [OrderController::class, 'printPdf'])->name('order.print.pdf');
        route::get('order/sendPdf/{id}', [OrderController::class, 'sendPDF'])->name('order.send.pdf');
        route::get('shareInvoiceToEmail/{id?}', [OrderController::class, 'shareInvoiceToEmail'])->name('order.shareInvoiceToEmail');

        //order
        route::get('order/index/{slug?}', [OrderController::class, 'index'])->name('order.index');
        route::get('order/{id}/detail', [OrderController::class, 'show'])->name('order.detail');
        route::get('order/{id}/update/{slug}', [OrderController::class, 'update'])->name('order.update');
        route::get('order/assign/rider/{id}', [OrderController::class, 'assign'])->name('order.assign.rider');

        //notification
        route::get('/notification', [NotificationController::class, 'index'])->name('notification.index');
        route::get('/notification-delete/{id}', [NotificationController::class, 'destroy'])->name('notification.delete');
        route::get('/notification-delete-all', [NotificationController::class, 'deleteAll'])->name('notification.delete.all');


        Route::middleware(['supplier'])->group(function () {
            /* manage delivery areas */
            route::get('areas-index', [AreasController::class, 'index'])->name('areas.index');
            route::get('areas-create', [AreasController::class, 'create'])->name('areas.create');
            route::post('add-areas', [AreasController::class, 'store'])->name('areas.store');
            route::get('delete-areas/{id}', [AreasController::class, 'destroy'])->name('areas.destroy');
            route::get('edit-areas/{id}', [AreasController::class, 'edit'])->name('areas.edit');


            //Payment Profile Routes
            Route::get('payment-profile', [WithDrawController::class, 'paymentProfile'])->name('payment-profile');
            Route::post('withdraw-payment', [WithDrawController::class, 'withdrawPayment'])->name('store.withdraw-payment');
            Route::post('update/payment-profile', [WithDrawController::class, 'updatePaymentProfile'])->name('store.update.payment-profile');


            //Review routes
            Route::get('all-reviews', [ReviewController::class, 'get'])->name('store.reviews');


            /* manage colors */
            route::get('color-index', [ColorController::class, 'index'])->name('color.index');
            route::post('add-color', [ColorController::class, 'save'])->name('color.save');

            //Packages  --//featured Packages
            route::get('/subscription-type', [SubscriptionController::class, 'subscriptionType'])->name('subscription.type');
            route::get('/subscription-packages', [SubscriptionController::class, 'index'])->name('packages.index');
            route::get('/featured-packages', [SubscriptionController::class, 'featuredPackages'])->name('featured.packages.index');
            route::post('/subscription-type-submit', [UserSubscriptionController::class, 'checkStream'])->name('subscription.type.submit');
            route::post('subscription/payment', [UserSubscriptionController::class, 'subscribe'])->name('subscription.payment');
            route::get('/subscription-payment-response', [UserSubscriptionController::class, 'paymentResponse'])->name('subscription.payment-response');

            route::post('featured-subscription/payment', [UserSubscriptionController::class, 'featuredSubscribe'])->name('featured.subscription.payment');
            route::get('/featured-subscription-payment-response', [UserSubscriptionController::class, 'featuredPaymentResponse'])->name('featured.subscription.payment-response');

        });

        Route::middleware(['check_subscription', 'supplier'])->group(function () {
            //Manage Products
            route::get('/product', [ProductController::class, 'all'])->name('product.index');
            route::get('/product/add-edit/{id}', [ProductController::class, 'create'])->name('product.create');
            route::post('/product/save/{id}', [ProductController::class, 'save'])->name('product.save');
            route::get('/product-edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
        });
    });


    Route::get('command', function () {
        Artisan::call('clear:all');
        dd("Done");
    });

    Route::get('migrate', function () {
        Artisan::call('migrate');
        dd("Done");
    });

    Route::get('migrate-fresh', function () {
        Artisan::call('migrate:refresh');
        dd("Done");
    });

    Route::get('db-seed', function () {
        Artisan::call('db:seed');
        dd("Done");
    });
});
