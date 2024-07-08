<?php

use App\Http\Controllers\Api\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IndexController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\AreasController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\WithdrawsController;
use App\Http\Controllers\Api\CitiesController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\RidersController;
use App\Http\Controllers\Api\Store\ProductController;
use App\Http\Controllers\Api\SubscriptionPackagesController;
use App\Http\Controllers\Api\UserSubscriptionsController;
use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CheckOutController;
use App\Http\Controllers\Api\OrdersController;



Route::namespace('api')->as('api.')->group(function () {
    Route::as('auth.')->group(function () {
        Route::post('register', [UsersController::class, 'register'])->name('register');
        Route::post('login', [UsersController::class, 'login'])->name('login');
        Route::post('user/login', [UsersController::class, 'social_login'])->name('users.login');
        Route::post('forgot-password', [UsersController::class, 'forgotPassword'])->name('forgot-password');
        Route::post('reset-password', [UsersController::class, 'resetPassword'])->name('reset-password');

        Route::middleware(['jwt.verify'])->group(function () {

            /*Notification*/
            Route::get('notifications', [NotificationController::class, 'notifications'])->name('notification.all');
            Route::get('notifications-count', [NotificationController::class, 'notificationCount'])->name('notification.count');
            Route::get('notification-seen', [NotificationController::class, 'isSeen'])->name('notification.seen');
            Route::get('notification-view/{notificationId}', [NotificationController::class, 'isViewed'])->name('notification.viewed');
            Route::post('notification-delete', [NotificationController::class, 'deleteNotification'])->name('notification.delete');


            Route::get('supplier-detail/{id}', [IndexController::class, 'suppliersDetail'])->name('supplier.detail');
            Route::get('products', [IndexController::class, 'products'])->name('products');
            Route::get('product-detail/{id}', [IndexController::class, 'productDetail'])->name('product.details');
            Route::post('verify-email', [UsersController::class, 'verifyEmail'])->name('verify-email');
            Route::post('logout', [UsersController::class, 'logout'])->name('logout');
            Route::get('resend-verification-code', [UsersController::class, 'resendVerificationCode'])->name('resend-verification-code');

            Route::middleware(['user'])->group(function () {

                //Reviews
                Route::post('reviews', [ReviewController::class, 'save'])->name('store.create-reviews');


                //Ads To Cart Routes
                Route::get('cart', [CartController::class, 'index'])->name('cart.index');
                route::post('add-to-cart', [CartController::class, 'save'])->name('cart.add');
                route::post('update-cart', [CartController::class, 'update'])->name('cart.update');
                route::get('delete-cart/{id}', [CartController::class, 'delete'])->name('cart.delete');
                /*checkout controller*/
                route::get('check-out', [CheckOutController::class, 'index'])->name('checkout.index');
                route::post('buy-now', [CheckOutController::class, 'save'])->name('checkout.buy-now');

            });

            Route::middleware(['delivery_Company'])->group(function () {
                //Riders
                route::get('/riders', [RidersController::class, 'index'])->name('riders');
                route::get('riders/{id}/edit', [RidersController::class, 'edit'])->name('edit.riders');
                route::post('update/{id}/rider', [RidersController::class, 'update'])->name('update.riders');
                route::delete('riders/delete/{id}', [RidersController::class, 'destroy'])->name('destroy.riders');
            });

            route::post('areas-index', [AreasController::class, 'index'])->name('areas.index');


            Route::middleware(['email_verified'])->group(function () {
                Route::post('change-password', [UsersController::class, 'changePassword'])->name('change-password');
                Route::get('profile/data', [UsersController::class, 'editProfile'])->name('get.profile');
                Route::post('update/profile', [UsersController::class, 'updateProfile'])->name('update.profile');
                Route::get('subscription/packages', [SubscriptionPackagesController::class, 'index'])->name('subscription-package');
                Route::get('subscription/payment', [UserSubscriptionsController::class, 'paymentResponse'])->name('subscription.payment-response');


                //Reviews
                Route::get('all-reviews', [ReviewController::class, 'get'])->name('store.reviews');


                //Payment Profile
                Route::get('payment-profile', [WithdrawsController::class, 'paymentProfile'])->name('payment-profile');
                Route::post('withdraw-payment', [WithdrawsController::class, 'withdrawPayment'])->name('store.withdraw-payment');
                Route::post('update/payment-profile', [WithdrawsController::class, 'updatePaymentProfile'])->name('store.update.payment-profile');

                /* manage address */
                route::get('address-index', [AddressController::class, 'index'])->name('address.index');
                route::post('add-address/{id}', [AddressController::class, 'store'])->name('address.store');
                route::post('make-default', [AddressController::class, 'makeDefault'])->name('address.make-default');
                route::get('delete-address/{id}', [AddressController::class, 'destroy'])->name('address.destroy');
                route::get('edit-address/{id}', [AddressController::class, 'edit'])->name('address.edit');
                Route::get('set/user/settings', [IndexController::class, 'setUserSettings'])->name('set.user.settings');

                //order listing
                route::get('/order/listing/{slug?}', [OrdersController::class, 'index']);
                route::get('/order/detail/{id}', [OrdersController::class, 'get']);
                route::get('/order/{id}/update/{slug}', [OrdersController::class, 'update']);
                route::get('/order/{id}/pdf/invoice', [OrdersController::class, 'printPdf']);
                route::get('/order/{id}/pdf/invoice/send', [OrdersController::class, 'sendPdf']);
                route::get('/order/assign/rider', [OrdersController::class, 'assign'])->name('order.assign.rider');
                Route::get('/supplier/{id}/detail', [IndexController::class, 'supplierDetail'])->name('supplier.detail');

                /* manage delivery areas */
                route::get('areas-create', [AreasController::class, 'create'])->name('areas.create');
                route::post('add-areas', [AreasController::class, 'store'])->name('areas.store');
                route::get('delete-areas/{id}', [AreasController::class, 'destroy'])->name('areas.destroy');
                route::get('edit-areas/{id}', [AreasController::class, 'edit'])->name('areas.edit');

                Route::get('featured/payment', [UserSubscriptionsController::class, 'featuredPaymentResponse'])->name('featured.payment-response');
                route::get('all-featured-packages', [UserSubscriptionsController::class, 'allFeaturedPack'])->name('all.featured.packages');
                route::get('all-purchase-packages', [UserSubscriptionsController::class, 'buyFeaturedPackage'])->name('buy.featured.packages');
            });

            Route::middleware(['check_subscription', 'supplier'])->group(function () {
                route::get('/product', [ProductController::class, 'all'])->name('product.index');
                route::get('/categories', [ProductController::class, 'categories'])->name('categories');
                route::get('/list-purchase-package', [ProductController::class, 'listOfPurchasePackage'])->name('list.purchase.package');
                route::post('product/save/{id}', [ProductController::class, 'save'])->name('product.save');
                Route::post('product-delete', [ProductController::class, 'delete'])->name('product.delete');
                route::get('product-edit/{id}', [ProductController::class, 'edit'])->name('product.edit');
            });
        });
    });

    Route::get('settings', [SettingController::class, 'settings'])->name('settings');
    Route::get('cities', [CitiesController::class, 'cities'])->name('get.cities');
    Route::get('/areas/{id?}', [IndexController::class, 'areas'])->name('areas');
    Route::post('remove-image', [IndexController::class, 'removeImage'])->name('remove-image');
    Route::post('upload-image', [IndexController::class, 'uploadImage'])->name('upload-image');
    Route::get('sub-categories/{id?}', [CategoryController::class, 'getSubcategories'])->name('categories.sub-categories');
    Route::get('suppliers', [IndexController::class, 'suppliers'])->name('suppliers');
    Route::post('contact_us/submit', [IndexController::class, 'contactSubmit'])->name('contact_us.submit');
    Route::get('offers', [IndexController::class, 'offers'])->name('offers');
    Route::get('articles', [IndexController::class, 'articles'])->name('articles');
    Route::get('article/{slug}', [IndexController::class, 'articleDetail'])->name('article.detail');
    Route::get('gallery', [IndexController::class, 'gallery'])->name('gallery');
    Route::get('faqs', [IndexController::class, 'faqs'])->name('faqs');
    Route::get('pages/{slug?}', [IndexController::class, 'pages'])->name('pages');
    Route::get('DeliveryCompanies', [IndexController::class, 'deliveryCompanies'])->name('deliveryCompanies');

    route::get('/city/{id}/{store_id}', [IndexController::class, 'city'])->name('city');
    //Categories
    Route::get('categories', [CategoryController::class, 'getCategories'])->name('categories');
    Route::get('sub-categories/{id?}', [CategoryController::class, 'getSubcategories'])->name('categories.sub-categories');
    Route::get('subCategories', [CategoryController::class, 'getSubCateWithAttr'])->name('get.subCategories');
    Route::post('upload-multi-image', [IndexController::class, 'multiImageUpload'])->name('multi.image.uploader');
});
