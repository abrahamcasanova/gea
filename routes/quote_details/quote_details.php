<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'QuoteDetails'], function() {
        // views
        Route::group(['prefix' => 'quote_details'], function() {
            Route::view('/', 'quote_details.index')->middleware('permission:read-quotes');
            Route::view('/create', 'quote_details.create')->middleware('permission:create-quotes');
            Route::view('/{quote_detail}/edit', 'quote_details.edit')->middleware('permission:update-quotes');
            Route::view('/{quote_detail}/quote', 'quote_details.quote')->middleware('permission:create-quotes');
        });

        // api
        Route::group(['prefix' => 'api/quote_details'], function() {
            Route::get('/getQuote/{quote_detail}', 'QuoteDetailController@getQuote');
            Route::get('/all', 'QuoteDetailController@all');
            Route::get('/count', 'QuoteDetailController@count');
            Route::get('/{quote_detail}', 'QuoteDetailController@show')->middleware('permission:read-quotes');
            Route::get('/order/{quote_detail}', 'QuoteDetailController@order')->middleware('permission:read-quotes');
            Route::post('/by-quote-id', 'QuoteDetailController@getQuoteDetailsByQuoteId');
            Route::post('/store', 'QuoteDetailController@store')->middleware('permission:create-quotes');
            Route::post('/order', 'QuoteDetailController@createOrder')->middleware('permission:create-quotes');
            Route::post('/filter', 'QuoteDetailController@filter')->middleware('permission:read-quotes');
            Route::put('/update/{quote_detail}', 'QuoteDetailController@update')->middleware('permission:update-quotes');
            Route::delete('/{quote_detail}', 'QuoteDetailController@destroy');
        });
    });
});
