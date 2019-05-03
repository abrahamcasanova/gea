<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Reports'], function() {
        // views
        Route::group(['prefix' => 'reports'], function() {
            Route::view('/', 'reports.index')->middleware('permission:read-reports');
            Route::view('/create', 'reports.create')->middleware('permission:create-reports');
            Route::view('/{report}/edit', 'reports.edit')->middleware('permission:update-reports');
            Route::view('/track-report', 'reports.track')->middleware('permission:create-reports');
            Route::view('/{report}/report', 'reports.report')->middleware('permission:create-reports');
        });

        // api
        Route::group(['prefix' => 'api/reports'], function() {
            Route::get('/all', 'ReportController@all');
            Route::get('/store-order', 'ReportController@storeOrder');
            Route::get('/count', 'ReportController@count');
            Route::get('/{report}', 'ReportController@show')->middleware('permission:read-reports');
            Route::get('/order/{report}', 'ReportController@order');
            Route::post('/get-track', 'ReportController@getTrack')->middleware('permission:create-reports');
            Route::post('/store-track', 'ReportController@storeTrack')->middleware('permission:create-reports');
            Route::post('/store', 'ReportController@store')->middleware('permission:create-reports');
            Route::post('/export', 'ReportController@getReport');
            Route::post('/show-chart', 'ReportController@showChart');
            Route::post('/send-report', 'ReportController@sendReport');
            Route::post('/order', 'ReportController@createOrder')->middleware('permission:create-reports');
            Route::post('/filter', 'ReportController@filter')->middleware('permission:read-reports');
            Route::put('/update/{report}', 'ReportController@update')->middleware('permission:update-reports');
            Route::delete('/{report}', 'ReportController@destroy')->middleware('permission:delete-reports');
        });
    });
});
