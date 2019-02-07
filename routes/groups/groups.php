<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Groups'], function() {
        // views
        Route::group(['prefix' => 'groups'], function() {
            Route::view('/', 'groups.index')->middleware('permission:read-groups');
            Route::view('/create', 'groups.create')->middleware('permission:create-groups');
            Route::view('/{group}/edit', 'groups.edit')->middleware('permission:update-groups');
        });

        // api
        Route::group(['prefix' => 'api/groups'], function() {
            Route::get('/getUserRoles/{user}', 'GroupController@getUserRoles');
            Route::get('/count', 'GroupController@count');
            Route::get('/all', 'GroupController@all');
            Route::get('/{user}', 'GroupController@show')->middleware('permission:read-groups');
            Route::post('/subgroup_by_gruoup_id', 'GroupController@subgroup');
            Route::post('/filter', 'GroupController@filter')->middleware('permission:read-groups');
            Route::post('/store', 'GroupController@store')->middleware('permission:create-groups');
            Route::put('/update/{user}', 'GroupController@update')->middleware('permission:update-groups');
            Route::delete('/{user}', 'GroupController@destroy')->middleware('permission:delete-groups');
        });
    });
});
