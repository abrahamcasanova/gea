<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'CustomerOrder'], function() {
        // views
        Route::group(['prefix' => 'customers_orders'], function() {
            Route::view('/', 'customers_orders.index')->middleware('permission:read-customer-orders');
            Route::view('/create', 'customers_orders.create')->middleware('permission:create-customer-orders');
            Route::view('/{customer_order}/edit', 'customers_orders.edit')->middleware('permission:update-customer-orders');
            Route::view('/{customer_order}/quote', 'customers_orders.quote')->middleware('permission:create-customer-orders');
        });

        // api
        Route::group(['prefix' => 'api/customers_orders'], function() {
            Route::get('/getQuote/{customer_order}', 'CustomerOrderController@getQuote');
            Route::get('/all', 'CustomerOrderController@all');
            Route::get('/store-order', 'CustomerOrderController@storeOrder');
            Route::get('/count', 'CustomerOrderController@count');
            Route::get('/{customer_order}', 'CustomerOrderController@show')->middleware('permission:read-customer-orders');
            Route::get('/order/{customer_order}', 'CustomerOrderController@order');
            Route::post('/store', 'CustomerOrderController@store')->middleware('permission:create-customer-orders');
            Route::post('/order', 'CustomerOrderController@createOrder')->middleware('permission:create-customer-orders');
            Route::post('/filter', 'CustomerOrderController@filter')->middleware('permission:read-customer-orders');
            Route::put('/update/{customer_order}', 'CustomerOrderController@update')->middleware('permission:update-customer-orders');
            Route::delete('/{customer_order}', 'CustomerOrderController@destroy')->middleware('permission:delete-customer-orders');
        });
    });
});
