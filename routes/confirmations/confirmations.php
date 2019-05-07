<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Confirmations'], function() {
        // views
        Route::group(['prefix' => 'confirmations'], function() {
            Route::view('/', 'confirmations.index')->middleware('permission:read-confirmations');
            Route::view('/create', 'confirmations.create')->middleware('permission:create-confirmations');
            Route::view('/{customer}/edit', 'confirmations.edit')->middleware('permission:update-confirmations');
        });

        // api
        Route::group(['prefix' => 'api/confirmations'], function() {
            Route::get('/getCustomer/{customer}', 'ConfirmController@getCustomer');
            Route::get('/store-order', 'ConfirmController@storeOrder');
            Route::get('/count', 'ConfirmController@count');
            Route::get('/{customer}', 'ConfirmController@show')->middleware('permission:read-confirmations');
            Route::get('/order/{customer}', 'ConfirmController@order');
            Route::post('/getDetails', 'ConfirmController@getDetails');
            Route::post('/order/whatsapp/', 'ConfirmController@orderWhatsapp');
            Route::post('/store', 'ConfirmController@store')->middleware('permission:create-confirmations');
            Route::post('/order', 'ConfirmController@createOrder')->middleware('permission:create-confirmations');
            Route::post('/filter', 'ConfirmController@filter')->middleware('permission:read-confirmations');
            Route::post('/upload', 'ConfirmController@upload')->middleware('permission:read-confirmations');
            Route::post('/docs', 'ConfirmController@getDocs')->middleware('permission:read-confirmations');
            Route::put('/update/{customer}', 'ConfirmController@update')->middleware('permission:update-confirmations');
            Route::delete('/{customer}', 'ConfirmController@destroy')->middleware('permission:delete-confirmations');
        });
    });
});

Route::view('customers/{customer}/order', 'customers.order')->name('customers.order')->middleware('signed');
Route::group(['namespace' => 'Customers'], function() {
    Route::group(['prefix' => 'api/customers'], function() {
        Route::post('/store-order', 'ConfirmController@storeOrder');
        Route::post('/send-received/', 'ConfirmController@sendReceived')->name('send_received');
    });
});
