<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'SupplierPayment'], function() {
        // views
        Route::group(['prefix' => 'supplier_payments'], function() {
            Route::view('/', 'supplier_payments.index')->middleware('permission:read-supplier-payments');
            Route::view('/create/{id}', 'supplier_payments.create')->middleware('permission:create-supplier-payments');
            Route::view('/{payment}/edit', 'supplier_payments.edit')->middleware('permission:update-supplier-payments');
        });

        // api
        Route::group(['prefix' => 'api/supplier_payments'], function() {
          Route::post('/get-payments/{id}', 'SupplierPaymentController@getPayments');
          Route::post('/store', 'SupplierPaymentController@store')->middleware('permission:create-supplier-payments');
        });
    });
});
