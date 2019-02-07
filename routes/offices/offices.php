<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Offices'], function() {
        // views
        Route::group(['prefix' => 'offices'], function() {
            Route::view('/', 'offices.index')->middleware('permission:read-offices');
            Route::view('/create', 'offices.create')->middleware('permission:create-offices');
            Route::view('/{group}/edit', 'offices.edit')->middleware('permission:update-offices');
        });

        // api
        Route::group(['prefix' => 'api/offices'], function() {
            Route::get('/getUserRoles/{office}', 'OfficeController@getUserRoles');
            Route::get('/count', 'OfficeController@count');
            Route::get('/all', 'OfficeController@all');
            Route::get('/{office}', 'OfficeController@show')->middleware('permission:read-offices');
            Route::post('/subgroup_by_gruoup_id', 'OfficeController@subgroup');
            Route::post('/filter', 'OfficeController@filter')->middleware('permission:read-offices');
            Route::post('/store', 'OfficeController@store')->middleware('permission:create-offices');
            Route::put('/update/{office}', 'OfficeController@update')->middleware('permission:update-offices');
            Route::delete('/{office}', 'OfficeController@destroy')->middleware('permission:delete-offices');
        });
    });
});
