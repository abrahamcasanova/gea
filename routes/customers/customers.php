<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Customers'], function() {
        // views
        Route::group(['prefix' => 'customers'], function() {
            Route::view('/', 'customers.index')->middleware('permission:read-customers');
            Route::view('/create', 'customers.create')->middleware('permission:create-customers');
            Route::view('/{customer}/edit', 'customers.edit')->middleware('permission:update-customers');
        });

        // api
        Route::group(['prefix' => 'api/customers'], function() {
            Route::get('/getCustomer/{customer}', 'CustomerController@getCustomer');
            Route::get('/count', 'CustomerController@count');
            Route::get('/{customer}', 'CustomerController@show')->middleware('permission:read-customers');
            Route::post('/store', 'CustomerController@store')->middleware('permission:create-customers');
            Route::post('/filter', 'CustomerController@filter')->middleware('permission:read-customers');
            Route::post('/upload', 'CustomerController@upload')->middleware('permission:read-customers');
            Route::post('/docs', 'CustomerController@getDocs')->middleware('permission:read-customers');
            Route::put('/update/{customer}', 'CustomerController@update')->middleware('permission:update-customers');
            Route::delete('/{customer}', 'CustomerController@destroy')->middleware('permission:delete-customers');
        });
    });
});
