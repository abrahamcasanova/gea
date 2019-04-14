<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Roles'], function() {

        // views
        Route::group(['prefix' => 'permissions'], function() {
            Route::view('/', 'permissions.index')->middleware('permission:read-roles');
            Route::view('/create', 'permissions.create')->middleware('permission:create-roles');
            Route::view('/{permision}/edit', 'permissions.edit')->middleware('permission:update-roles');
        });

        // api
        Route::group(['prefix' => 'api/permissions'], function() {
            Route::get('/permission/{permissionName}', 'PermissionController@check');
            Route::get('/count', 'PermissionController@count');
            Route::get('/getModules', 'PermissionController@getModules');
            Route::get('/getPermissionModulesPermissions/{permission}','PermissionController@getPermissionModulesPermissions');
            Route::post('/store', 'PermissionController@store')->middleware('permission:create-roles');
            Route::post('/filter', 'PermissionController@filter')->middleware('permission:read-roles');
            Route::put('/update/{permission}', 'PermissionController@update')->middleware('permission:update-roles');
            Route::delete('/{permission}', 'PermissionController@destroy')->middleware('permission:delete-roles');

        });
    });
});
