<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('admin')->group(function () {
    Route::controller(\App\Http\Controllers\Admin\AuthController::class)->prefix('auth')->group(function () {
        Route::post('/login', 'login')->name('login');
    });
});
Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('admin')->group(function () {
        Route::controller(\App\Http\Controllers\Admin\AuthController::class)->prefix('auth')->group(function () {
            Route::get('/me', 'me')->name('admin.auth.me');
            Route::post('/logout', 'logout')->name('admin.auth.logout');
        });
        Route::controller(\App\Http\Controllers\Admin\MerchantController::class)->prefix('merchants')->group(function () {
            Route::post('/store', 'store')->name('admin.merchant.store');
            Route::put('/update', 'update')->name('admin.merchant.update');
            Route::get('/index', 'index')->name('admin.merchant.index');
            Route::get('/show', 'show')->name('admin.merchant.show');
            Route::delete('/destroy', 'destroy')->name('admin.merchant.destroy');
        });
        Route::controller(\App\Http\Controllers\Admin\ApplicationController::class)->prefix('applications')->group(function () {
            Route::post('/store', 'store')->name('admin.application.store');
            Route::put('/update', 'update')->name('admin.application.update');
            Route::get('/index', 'index')->name('admin.application.index');
            Route::get('/show', 'show')->name('admin.application.show');
            Route::put('/refresh-secure-key', 'refreshSecureKey')->name('admin.application.refreshSecureKey');
            Route::delete('/destroy', 'destroy')->name('admin.application.destroy');
        });
        Route::controller(\App\Http\Controllers\Admin\PayOrderController::class)->prefix('pay-orders')->group(function () {
            Route::get('/index', 'index')->name('admin.pay-order.index');
            Route::get('/show', 'show')->name('admin.pay-order.show');
        });
    });
});

Route::prefix('web')->group(function () {
    Route::controller(\App\Http\Controllers\Web\PayOrderController::class)->prefix('pay-orders')->group(function () {
        Route::get('/show', 'show')->name('admin.pay-order.show');
        Route::post('/store', 'store')->name('admin.pay-order.store');
        Route::get('/submitted', 'submitted')->name('admin.pay-order.submitted');
    });
});
