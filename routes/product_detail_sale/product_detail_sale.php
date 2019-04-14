<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'ProductDetailSale'], function() {
        // views
        Route::group(['prefix' => 'product_detail_sales'], function() {
            Route::view('/', 'products.index')->middleware('permission:read-products');
            Route::view('/create', 'products.create')->middleware('permission:create-products');
            Route::view('/{product_detail_sale}/edit', 'products.edit')->middleware('permission:update-products');
        });

        // api
        Route::group(['prefix' => 'api/product_detail_sales'], function() {
            Route::get('/get-product-detail-sale/{product_detail_sale}', 'ProductDetailSalesController@getProductDetailSale');
            Route::get('/count', 'ProductDetailSalesController@count');
            Route::get('/all', 'ProductDetailSalesController@all');
            Route::post('/get-by-quote', 'ProductDetailSalesController@getByQuote');
            Route::post('/export-word/{product_detail_sale}', 'ProductDetailSalesController@exportWord')->middleware('permission:read-products');
            Route::post('/filter', 'ProductDetailSalesController@filter')->middleware('permission:read-products');
            Route::post('/by-quote/{quoteId}', 'ProductDetailSalesController@getProductByQuote')
                ->middleware('permission:read-products');
            Route::post('/by-quote-special/{quoteId}', 'ProductDetailSalesController@getProductByQuoteSpecial')
                ->middleware('permission:read-products');
            Route::get('/{product_detail_sale}', 'ProductDetailSalesController@show')->middleware('permission:read-products');
            Route::post('/store', 'ProductDetailSalesController@store')->middleware('permission:create-products');
            Route::put('/update/{product_detail_sale}', 'ProductDetailSalesController@update')->middleware('permission:update-products');
            Route::delete('/{product_detail_sale}', 'ProductDetailSalesController@destroy')->middleware('permission:delete-products');
        });
    });
});
