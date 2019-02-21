<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Products'], function() {
        // views
        Route::group(['prefix' => 'products'], function() {
            Route::view('/', 'products.index')->middleware('permission:read-products');
            Route::view('/create', 'products.create')->middleware('permission:create-products');
            Route::view('/{product}/edit', 'products.edit')->middleware('permission:update-products');
        });

        // api
        Route::group(['prefix' => 'api/products'], function() {
            Route::get('/get-product/{product}', 'ProductController@getProduct');
            Route::get('/count', 'ProductController@count');
            Route::post('/filter', 'ProductController@filter')->middleware('permission:read-products');

            Route::get('/{product}', 'ProductController@show')->middleware('permission:read-products');
            Route::post('/store', 'ProductController@store')->middleware('permission:create-products');
            Route::put('/update/{product}', 'ProductController@update')->middleware('permission:update-products');
            Route::delete('/{product}', 'ProductController@destroy')->middleware('permission:delete-products');
        });
    });
});
