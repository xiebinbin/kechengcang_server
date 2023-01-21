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
        Route::post('upload', '\App\Http\Controllers\Admin\UploadController@index')->name('admin.upload');
        Route::controller(\App\Http\Controllers\Admin\EditorJsController::class)->prefix('editor-js')->group(function () {
            Route::post('/upload-file', 'uploadFile')->name('admin.editor-js.uploadFile');
            Route::post('/upload-image', 'uploadImage')->name('admin.editor-js.uploadImage');
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

        Route::controller(\App\Http\Controllers\Admin\ChannelController::class)->prefix('channels')->group(function () {
            Route::post('/store', 'store')->name('admin.channels.store');
            Route::put('/update', 'update')->name('admin.channels.update');
            Route::get('/index', 'index')->name('admin.channels.index');
            Route::get('/show', 'show')->name('admin.channels.show');
            Route::put('/change-sort', 'changeSort')->name('admin.channels.changeSort');
            Route::delete('/destroy', 'destroy')->name('admin.channels.destroy');
            Route::get('/options-data', 'optionsData')->name('admin.channels.optionsData');
        });
        Route::controller(\App\Http\Controllers\Admin\SubjectController::class)->prefix('subjects')->group(function () {
            Route::post('/store', 'store')->name('admin.subjects.store');
            Route::put('/update', 'update')->name('admin.subjects.update');
            Route::get('/index', 'index')->name('admin.subjects.index');
            Route::get('/show', 'show')->name('admin.subjects.show');
            Route::put('/change-sort', 'changeSort')->name('admin.subjects.changeSort');
            Route::delete('/destroy', 'destroy')->name('admin.subjects.destroy');
            Route::get('/options-data', 'optionsData')->name('admin.subjects.optionsData');
        });
        Route::controller(\App\Http\Controllers\Admin\CategoryController::class)->prefix('categories')->group(function () {
            Route::post('/store', 'store')->name('admin.categories.store');
            Route::put('/update', 'update')->name('admin.categories.update');
            Route::get('/index', 'index')->name('admin.categories.index');
            Route::get('/show', 'show')->name('admin.categories.show');
            Route::put('/change-sort', 'changeSort')->name('admin.categories.changeSort');
            Route::delete('/destroy', 'destroy')->name('admin.categories.destroy');
            Route::get('/tree-data', 'treeData')->name('admin.categories.treeData');
        });
        Route::controller(\App\Http\Controllers\Admin\CourseController::class)->prefix('courses')->group(function () {
            Route::post('/store', 'store')->name('admin.courses.store');
            Route::get('/index', 'index')->name('admin.courses.index');
            Route::get('/show', 'show')->name('admin.courses.show');
            Route::put('/update', 'update')->name('admin.courses.update');
            Route::put('/change-sort', 'changeSort')->name('admin.courses.changeSort');
        });
    });
});

Route::prefix('web')->group(function () {
    Route::controller(\App\Http\Controllers\Web\PayOrderController::class)->prefix('pay-orders')->group(function () {
        Route::get('/show', 'show')->name('web.pay-order.show');
        Route::post('/store', 'store')->name('web.pay-order.store');
        Route::get('/submitted', 'submitted')->name('web.pay-order.submitted');
    });
    Route::controller(\App\Http\Controllers\Web\ChannelController::class)->prefix('channels')->group(function () {
        Route::get('/index', 'index')->name('web.channels.index');
    });
    Route::controller(\App\Http\Controllers\Web\SubjectController::class)->prefix('subjects')->group(function () {
        Route::get('/index', 'index')->name('web.subjects.index');
        Route::get('/tree', 'tree')->name('web.subjects.tree');
    });
    Route::controller(\App\Http\Controllers\Web\CourseController::class)->prefix('courses')->group(function () {
        Route::get('/index', 'index')->name('web.courses.index');
        Route::get('/show', 'show')->name('web.courses.show');
    });
});
