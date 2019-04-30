<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Payments'], function() {
        // views
        Route::group(['prefix' => 'payments'], function() {
            Route::view('/', 'payments.index')->middleware('permission:read-payments');
            Route::view('/create/{id}', 'payments.create')->middleware('permission:create-payments');
            Route::view('/{payment}/edit', 'payments.edit')->middleware('permission:update-payments');
        });

        // api
        Route::group(['prefix' => 'api/payments'], function() {
            Route::get('/get-payment/{payment}', 'PaymentController@getPayment');
            Route::get('/get-payments-by-sale/{payment}', 'PaymentController@getPaymentBySale');
            Route::get('/count', 'PaymentController@count');
            Route::get('/all', 'PaymentController@all');
            Route::post('/filter', 'PaymentController@filter')->middleware('permission:read-payments');
            Route::post('/by-quote/{quoteId}', 'PaymentController@getProductByQuote')
                ->middleware('permission:read-payments');
            Route::post('/by-quote-special/{quoteId}', 'PaymentController@getProductByQuoteSpecial')
                ->middleware('permission:read-payments');
            Route::get('/{payment}', 'PaymentController@show')->middleware('permission:read-payments');
            Route::post('/store', 'PaymentController@store')->middleware('permission:create-payments');
            Route::post('/save-confirm', 'PaymentController@saveConfirm');
            Route::put('/update/{payment}', 'PaymentController@update')->middleware('permission:update-payments');
            Route::delete('/{payment}', 'PaymentController@destroy')->middleware('permission:delete-payments');
        });
    });
});
