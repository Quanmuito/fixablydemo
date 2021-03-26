<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function() {
    return view('welcome');
})->name('welcome');

Route::get('/orders/pagination/{page}', 'OrdersController@index')->name('orders.index');
Route::get('/orders/show/{id}', 'OrdersController@show')->name('orders.show');

Route::get('/orders/search', 'OrdersController@search')->name('orders.search');
Route::post('/orders/search', 'OrdersController@searchHandle')->name('orders.searchHandle');
Route::get('/orders/search/{type}/{criteria}/pagination/{page}/{full}', 'OrdersController@searchResult')->name('orders.searchResult');

Route::get('/orders/create', 'OrdersController@create')->name('orders.create');
Route::post('/orders/create', 'OrdersController@createHandle')->name('orders.createHandle');
Route::post('/orders/create/note', 'OrdersController@createNote')->name('orders.createNote');

Route::get('/invoices', 'InvoicesController@invoices')->name('invoices.invoices');
Route::post('/invoices', 'InvoicesController@invoicesHandle')->name('invoices.invoicesHandle');
Route::get('/invoices/{from}/{to}', 'InvoicesController@invoicesResult')->name('invoices.invoicesResult');