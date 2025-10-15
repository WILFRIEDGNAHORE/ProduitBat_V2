<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function () {
    //Show Login form
    Route::get('login', [AdminController::class, 'create'])->name('admin.login');
    //Handle Login form
    Route::post('login', [AdminController::class, 'store'])->name('admin.login.request');
    Route::group(['middleware' => ['admin']], function () {


        // Dashboard Route
        Route::resource('dashboard', AdminController::class)->only(['index']);


        //display update password page
        Route::get('update-password', [AdminController::class, 'edit'])->name('admin.update-password');
        //verify password Route
        Route::post('verify-password', [AdminController::class, 'verifyPassword'])->name('admin.verify-password');
        //update password Route
        Route::post('admin/update-password', [AdminController::class, 'updatePasswordRequest'])->name('admin.update-password.request');


        // Display Update Admin Details
        Route::get('update-details', [AdminController::class, 'editDetails'])->name('admin.update-details');
        //update details Route
        Route::post('admin/update-details', [AdminController::class, 'updateDetails'])->name('admin.update-details.request');
        //delete profile image Route
        Route::post('delete-profile-image', [AdminController::class, 'deleteProfileImage']);


        //subadmins Route
        Route::get('subadmins', [AdminController::class, 'subadmins']);


        Route::post('update-subadmin-status', [AdminController::class, 'updateSubadminStatus']);
        Route::get('add-edit-subadmin/{id?}', [AdminController::class, 'addEditSubadmin']);
        Route::post('add-edit-subadmin/request', [
            AdminController::class,
            'addEditSubadminRequest'
        ]);

        Route::get('delete-subadmin/{id}', [AdminController::class, 'deleteSubadmin']);

        Route::get('/update-role/{id}', [AdminController::class, 'updateRole']);
        Route::post('/update-role/request', [AdminController::class, 'updateRoleRequest']);


        //Categories
        Route::resource('categories', CategoryController::class);
        Route::post('update-category-status', [CategoryController::class, 'updateCategoryStatus']);
        Route::post('delete-category-image', [CategoryController::class, 'deleteCategoryImage']);
        Route::post('delete-size-chart', [CategoryController::class, 'deleteSizeChart']);

        //Products
        Route::resource('products', ProductController::class);
        Route::post('update-product-status', [ProductController::class, 'updateProductStatus']);
        Route::post('update-attribute-status', [ProductController::class, 'updateAttributeStatus']);
        Route::get('delete-product-attribute/{id}', [ProductController::class, 'deleteProductAttribute']);

        Route::post('/product/upload-image', [ProductController::class, 'uploadimage'])->name('product.upload.image');
        Route::post('/product/upload-images', [ProductController::class, 'uploadImages'])->name('product.upload.images');
        Route::post('/product/delete-temp-image', [ProductController::class, 'deleteTemplmage'])->name('product.delete.temp.image');
        Route::get('delete-product-image/{id?}', [ProductController::class, 'deleteProductImage']);

        Route::post('/product/upload-video', [ProductController::class, 'uploadVideo'])->name('product.upload.video');
        Route::get('delete-product-main-image/{id}', [ProductController::class, 'deleteProductMainimage']);
        Route::get('delete-product-video/{id}', [ProductController::class, 'deleteProductVideo']);


        //logout Route
        Route::get('logout', [AdminController::class, 'destroy'])->name('admin.logout');
    });
});
