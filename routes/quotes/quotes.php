<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Quotes'], function() {
        // views
        Route::group(['prefix' => 'quotes'], function() {
            Route::view('/', 'quotes.index')->middleware('permission:read-quotes');
            Route::view('/create', 'quotes.create')->middleware('permission:create-quotes');
            Route::view('/{quote}/edit', 'quotes.edit')->middleware('permission:update-quotes');
            Route::view('/track-quote', 'quotes.track')->middleware('permission:create-quotes');
            Route::view('/{quote}/quote', 'quotes.quote')->middleware('permission:create-quotes');
        });

        // api
        Route::group(['prefix' => 'api/quotes'], function() {
            Route::get('/get-quote/{quote}', 'QuoteController@getQuote');
            Route::get('/all', 'QuoteController@all');
            Route::get('/store-order', 'QuoteController@storeOrder');
            Route::get('/count', 'QuoteController@count');
            Route::get('/{quote}', 'QuoteController@show')->middleware('permission:read-quotes');
            Route::get('by-id/{quote}', 'QuoteController@showWhereId')->middleware('permission:read-quotes');
            Route::get('/order/{quote}', 'QuoteController@order');
            Route::post('/get-track', 'QuoteController@getTrack')->middleware('permission:create-quotes');
            Route::post('/store-track', 'QuoteController@storeTrack')->middleware('permission:create-quotes');
            Route::post('/store', 'QuoteController@store')->middleware('permission:create-quotes');
            Route::post('/send-quote', 'QuoteController@sendQuote');
            Route::post('/order', 'QuoteController@createOrder')->middleware('permission:create-quotes');
            Route::post('/filter', 'QuoteController@filter')->middleware('permission:read-quotes');
            Route::put('/update/{quote}', 'QuoteController@update')->middleware('permission:update-quotes');
            Route::delete('/{quote}', 'QuoteController@destroy')->middleware('permission:delete-quotes');
        });
    });
});
