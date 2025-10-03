<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->group(function(){
    //Show Login form
    Route::get('login',[AdminController::class,'create'])->name('admin.login');
    //Handle Login form
    Route::post('login',[AdminController::class,'store'])->name('admin.login.request');
    // Dashboard Route
    Route::group(['middleware' => ['admin']], function () {
        
        Route::resource('dashboard', AdminController::class)->only(['index']);
        Route::get('update-password', [AdminController::class, 'edit'])->name('admin.update-password');

        Route::post('verify-password', [AdminController::class, 'verifyPassword'])->name('admin.verify-password');
        Route::get('logout',[AdminController::class,'destroy'])->name('admin.logout');

    });    

});
    
