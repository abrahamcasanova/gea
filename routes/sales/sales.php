<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Sales'], function() {
        // views
        Route::group(['prefix' => 'sales'], function() {
            Route::view('/', 'sales.index')->middleware('permission:read-sales');
            Route::view('/create', 'sales.create')->middleware('permission:create-sales');
            Route::view('/{sale}/edit', 'sales.edit')->middleware('permission:update-sales');
        });

        // api
        Route::group(['prefix' => 'api/sales'], function() {
            Route::get('/get-sale/{sale}', 'SaleController@getSale');
            Route::get('/count', 'SaleController@count');
            Route::get('/all', 'SaleController@all');
            Route::get('/coupon/{sale}', 'SaleController@generateCoupon');
            Route::post('/filter', 'SaleController@filter')->middleware('permission:read-sales');
            Route::post('/by-quote/{quoteId}', 'SaleController@getProductByQuote')
                ->middleware('permission:read-sales');
            Route::get('/{sale}', 'SaleController@show')->middleware('permission:read-sales');
            Route::post('/send-sale', 'SaleController@sendSale');
            Route::post('/store', 'SaleController@store')->middleware('permission:create-sales');
            Route::post('/save-note-coupon', 'SaleController@saveNoteCoupon');
            Route::put('/update/{sale}', 'SaleController@update')->middleware('permission:update-sales');
            Route::delete('/{sale}', 'SaleController@destroy')->middleware('permission:delete-sales');
        });
    });
});
