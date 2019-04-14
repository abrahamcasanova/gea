<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Destinations'], function() {
        // views
        Route::group(['prefix' => 'destinations'], function() {
            Route::view('/', 'destinations.index')->middleware('permission:read-destinations');
            Route::view('/create', 'destinations.create')->middleware('permission:create-destinations');
            Route::view('/{destination}/edit', 'destinations.edit')->middleware('permission:update-destinations');
        });

        // api
        Route::group(['prefix' => 'api/destinations'], function() {
            Route::get('/get-destination/{destination}', 'DestinationController@getDestination');
            Route::get('/count', 'DestinationController@count');
            Route::get('/all', 'DestinationController@all');
            Route::post('/search', 'DestinationController@search');
            Route::post('/export-word/{destination}', 'DestinationController@exportWord')->middleware('permission:read-destinations');
            Route::post('/filter', 'DestinationController@filter')->middleware('permission:read-destinations');
            Route::post('/by-quote/{quoteId}', 'DestinationController@getDestinationByQuote')
                ->middleware('permission:read-destinations');
            Route::get('/{destination}', 'DestinationController@show')->middleware('permission:read-destinations');
            Route::post('/store', 'DestinationController@store')->middleware('permission:create-destinations');
            Route::put('/update/{destination}', 'DestinationController@update')->middleware('permission:update-destinations');
            Route::delete('/{destination}', 'DestinationController@destroy')->middleware('permission:delete-destinations');
        });
    });
});
