<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'TypeOfPayments'], function() {
        // views
        Route::group(['prefix' => 'type_payments'], function() {
            Route::view('/', 'type_payments.index');
            Route::view('/create', 'type_payments.create');
            Route::view('/{type_payment}/edit', 'type_payments.edit');

        });

        // api
        Route::group(['prefix' => 'api/type_of_payments'], function() {
           
            Route::get('/get-type-payment/{id}', 'TypeOfPaymentController@getTypePayment');
            Route::get('/count', 'TypeOfPaymentController@count');
            Route::get('/all', 'TypeOfPaymentController@all');
            Route::post('/search', 'TypeOfPaymentController@search');
            Route::post('/export-word/{type-payment}', 'TypeOfPaymentController@exportWord')->middleware('permission:read-type-payments');
            Route::post('/filter', 'TypeOfPaymentController@filter')->middleware('permission:read-type-payments');
            Route::post('/by-quote/{quoteId}', 'TypeOfPaymentController@getDestinationByQuote')
                ->middleware('permission:read-type-payments');
            Route::get('/{type-payment}', 'TypeOfPaymentController@show')->middleware('permission:read-type-payments');
            Route::post('/store', 'TypeOfPaymentController@store')->middleware('permission:create-type-payments');
            Route::put('/update/{id}', 'TypeOfPaymentController@update')->middleware('permission:update-type-payments');
            Route::delete('/{id}', 'TypeOfPaymentController@destroy')->middleware('permission:delete-type-payments');

        });
    });
});
