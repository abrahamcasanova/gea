<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'GeneralConfig'], function() {
        // views
        Route::group(['prefix' => 'general_config'], function() {
            Route::view('/', 'general_config.index')->middleware('permission:read-general-config');
            Route::view('/create', 'general_config.create')->middleware('permission:read-general-config');
            Route::view('/{general_config}/edit', 'general_config.edit')->middleware('permission:update-general-config');
        });

        // api
        Route::group(['prefix' => 'api/general_config'], function() {
            Route::get('/all', 'GeneralConfigController@all');
            Route::post('/store', 'GeneralConfigController@store')->middleware('permission:read-general-config');
            Route::put('/update', 'GeneralConfigController@update')->middleware('permission:update-general-config');
            Route::delete('/{general_config}', 'GeneralConfigController@destroy')->middleware('permission:read-general-config');
        });
    });
});
