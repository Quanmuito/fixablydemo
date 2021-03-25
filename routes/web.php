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

Route::get('/orders/pagination/{page}', 'ProductsController@index')->name('product.index');
Route::get('/orders/show/{id}', 'ProductsController@show')->name('product.show');

Route::get('/orders/search', 'ProductsController@search')->name('product.search');
Route::post('/orders/search', 'ProductsController@searchHandle')->name('product.searchHandle');
Route::get('/orders/search/{type}/{criteria}/pagination/{page}/{full}', 'ProductsController@searchResult')->name('product.searchResult');

Route::get('/orders/create', 'ProductsController@create')->name('product.create');
Route::post('/orders/create', 'ProductsController@createHandle')->name('product.createHandle');
Route::post('/orders/create/note', 'ProductsController@createNote')->name('product.createNote');

Route::get('/invoices', 'ProductsController@invoicesSearch')->name('product.invoicesSearch');
Route::post('/invoices', 'ProductsController@invoicesHandle')->name('product.invoicesHandle');
Route::get('/invoices/{from}/{to}', 'ProductsController@invoices')->name('product.invoices');

