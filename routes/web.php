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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/personal-token', 'HomeController@personalToken')->name('personal-token');
Route::get('/home/clients-token', 'HomeController@clientsToken')->name('clients.token');
Route::get('/home/authorized-clients', 'HomeController@authorizedClientToken')->name('authorized.clients.token');
