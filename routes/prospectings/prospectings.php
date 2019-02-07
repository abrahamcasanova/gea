<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Prospectings'], function() {
        // views
        Route::group(['prefix' => 'prospectings'], function() {
            Route::view('/', 'prospectings.index')->middleware('permission:read-prospectings');
            Route::view('/create', 'prospectings.create')->middleware('permission:create-prospectings');
            Route::view('/track-prospecting', 'prospectings.track')->middleware('permission:create-prospectings');
            Route::view('/{prospecting}/edit', 'prospectings.edit')->middleware('permission:update-prospectings');
        });

        // api
        Route::group(['prefix' => 'api/prospectings'], function() {
            Route::get('/get-prospecting/{prospecting}', 'ProspectingController@getProspecting');
            Route::get('/count', 'ProspectingController@count');
            Route::get('/all-assistants', 'AssistantController@getAllAssistant');
            Route::get('/all', 'ProspectingController@all');
            Route::get('/{prospecting}', 'ProspectingController@show')->middleware('permission:read-prospectings');
            Route::post('/get-track', 'AssistantController@getTrack')->middleware('permission:create-prospectings');
            Route::post('/store', 'ProspectingController@store')->middleware('permission:create-prospectings');
            Route::post('/store-track', 'AssistantController@storeTrack')->middleware('permission:create-prospectings');
            Route::post('/filter', 'ProspectingController@filter')->middleware('permission:read-prospectings');
            Route::post('/upload', 'ProspectingController@upload')->middleware('permission:read-prospectings');
            Route::post('/docs', 'ProspectingController@getDocs')->middleware('permission:read-prospectings');
            Route::put('/update/{prospecting}', 'ProspectingController@update')->middleware('permission:update-prospectings');
            Route::delete('/{prospecting}', 'ProspectingController@destroy')->middleware('permission:delete-prospectings');
        });
    });
});
