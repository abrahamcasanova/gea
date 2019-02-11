<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['verify' => true]);

Route::get('/home', function () {
    return redirect('dashboard');
});

Route::get('/dashboard', 'DashboardController@index')->name('home')->middleware('verified');

require __DIR__ . '/profile/profile.php';
require __DIR__ . '/users/users.php';
require __DIR__ . '/roles/roles.php';
require __DIR__ . '/roles/permissions.php';
require __DIR__ . '/modules/modules.php';
require __DIR__ . '/customers/customers.php';
require __DIR__ . '/groups/groups.php';
require __DIR__ . '/offices/offices.php';
require __DIR__ . '/prospectings/prospectings.php';
require __DIR__ . '/products/products.php';
require __DIR__ . '/products_type/products_type.php';
require __DIR__ . '/suppliers/suppliers.php';
require __DIR__ . '/branches/branches.php';
