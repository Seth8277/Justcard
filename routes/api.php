<?php

use \Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'v1'], function () {
    Route::group(['middleware' => 'guest:api'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
    });
    Route::any('payment/notify/{$method}', 'PaymentController@notify');
    Route::get('payment/methods', 'PaymentController@methods');


    Route::get('category', 'CategoryController@show');
    Route::get('category/{category}', 'CategoryController@products');

    Route::get('product/{product}', 'ProductController@show');
    Route::post('product/{product}/buy', 'ProductController@buy');

    Route::get('order/{order}', 'OrderController@show');
    Route::get('order/{order}/pay/{method}','OrderController@pay');



    Route::group(['middleware' => 'refresh.token'], function () {
        Route::group(['middleware' => 'role:admin', 'prefix' => 'admin'], function () {
            Route::get('overview', 'OverviewController@show');

            Route::post('category/store/{category?}', 'CategoryController@store');

            Route::post('payment/store/{method?}','PaymentController@store');
            Route::get('payment/drivers','PaymentController@drivers');
            Route::get('payment/driver/{driver}','PaymentController@driver');

            Route::post('product/store/{product?}', 'ProductController@store');
            Route::get('product/{product}/cards', 'ProductController@cards');
            Route::post('product/{product}/cards/add', 'ProductController@add_cards');
        });
    });
});