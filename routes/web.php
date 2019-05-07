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
    return view('auth.login');
});

Auth::routes(['verify' => true]);

Route::get('/home', function () {
    return redirect('dashboard');
});

Route::get('customers/all-destinations', function()
{
    return App\Destination::Active()->get();
});

Route::group(['namespace' => 'Customers'], function() {
	Route::get('/customers/login', 'CustomerController@generateCustomerView');
	Route::get('/customers/cotiza', 'CustomerController@generateQuotePublic');
	Route::post('/customers/result', 'CustomerController@getPayments');
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
require __DIR__ . '/customer_orders/customer_orders.php';
require __DIR__ . '/branches/branches.php';
require __DIR__ . '/quotes/quotes.php';
require __DIR__ . '/quote_tracks/quote_tracks.php';
require __DIR__ . '/quote_details/quote_details.php';
require __DIR__ . '/sales/sales.php';
require __DIR__ . '/destinations/destinations.php';
require __DIR__ . '/product_detail_sale/product_detail_sale.php';
require __DIR__ . '/payments/payments.php';
require __DIR__ . '/type_of_payments/type_of_payments.php';
require __DIR__ . '/reports/reports.php';
require __DIR__ . '/general_config/general_config.php';
require __DIR__ . '/confirmations/confirmations.php';
