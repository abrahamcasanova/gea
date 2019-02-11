<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'ProductsType'], function() {
        // views
        Route::group(['prefix' => 'products_type'], function() {
            Route::view('/', 'products_type.index')->middleware('permission:read-products-type');
            Route::view('/create', 'products_type.create')->middleware('permission:create-products-type');
            Route::view('/{product_type}/edit', 'products_type.edit')->middleware('permission:update-products-type');
        });

        // api
        Route::group(['prefix' => 'api/products_type'], function() {
            Route::get('/get-product_type/{product_type}', 'ProductTypeController@getProductType');
            Route::get('/count', 'ProductTypeController@count');
            Route::post('/filter', 'ProductTypeController@filter')->middleware('permission:read-products-type');

            Route::get('/{product_type}', 'ProductTypeController@show')->middleware('permission:read-products-type');
            Route::post('/store', 'ProductTypeController@store')->middleware('permission:create-products-type');
            Route::put('/update/{product_type}', 'ProductTypeController@update')->middleware('permission:update-products-type');
            Route::delete('/{product_type}', 'ProductTypeController@destroy')->middleware('permission:delete-products-type');
        });
    });
});
