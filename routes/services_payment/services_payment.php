<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'ServicesPayment'], function() {

        // api
        Route::group(['prefix' => 'api/services-payment'], function() {
            Route::get('/get-service-payment/{services_payment}', 'ServicePaymentController@getService');
            Route::get('/all', 'ServicePaymentController@all');
            Route::post('/store', 'ServicePaymentController@store')->middleware('permission:create-services');
            Route::put('/update/{services_payment}', 'ServicePaymentController@update')->middleware('permission:update-services');
            Route::delete('/{services_payment}', 'ServicePaymentController@destroy')->middleware('permission:delete-services');
        });
    });
});
