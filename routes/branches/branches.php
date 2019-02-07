<?php
Route::middleware('auth')->group(function () {
    Route::group(['namespace' => 'Branches'], function() {
        // views
        Route::group(['prefix' => 'branches'], function() {
            Route::view('/', 'branches.index')->middleware('permission:read-branches');
            Route::view('/create', 'branches.create')->middleware('permission:create-branches');
            Route::view('/{branch}/edit', 'branches.edit')->middleware('permission:update-branches');
        });

        // api
        Route::group(['prefix' => 'api/branches'], function() {
            Route::get('/get-branch/{branch}', 'BranchController@getBranch');
            Route::get('/count', 'BranchController@count');
            Route::get('/all', 'BranchController@all');
            Route::get('/{branch}', 'BranchController@show')->middleware('permission:read-branches');
            Route::post('/store', 'BranchController@store')->middleware('permission:create-branches');
            Route::post('/filter', 'BranchController@filter')->middleware('permission:read-branches');
            Route::post('/upload', 'BranchController@upload')->middleware('permission:read-branches');
            Route::post('/docs', 'BranchController@getDocs')->middleware('permission:read-branches');
            Route::put('/update/{branch}', 'BranchController@update')->middleware('permission:update-branches');
            Route::delete('/{branch}', 'BranchController@destroy')->middleware('permission:delete-branches');
        });
    });
});
