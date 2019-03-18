<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'QuoteTrack'], function() {
        // api
        Route::group(['prefix' => 'api/quote-tracks'], function() {
        	Route::get('/count', 'QuoteTrackController@count');
            Route::post('/get-track', 'QuoteTrackController@getTrack')->middleware('permission:create-quotes');
            Route::post('/store-track', 'QuoteTrackController@storeTrack')->middleware('permission:create-quotes');
        });
    });
});
