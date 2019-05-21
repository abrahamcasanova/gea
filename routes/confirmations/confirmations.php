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
            Route::post('/getDetails', 'ConfirmController@getDetails');
            Route::delete('/{confirmation}', 'ConfirmController@destroy')
                ->middleware('permission:delete-confirmations');
        });
    });
});
