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

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/home/create', 'HomeController@create')->name('home.create');
Route::post('/home/store', 'HomeController@store')->name('home.store');
Route::get('/home/getdata', 'HomeController@getdata')->name('home.getdata');
Route::get('/home/getUser', 'HomeController@getUser')->name('home.getUser');
Route::post('/home/updateUser', 'HomeController@updateUser')->name('home.updateUser');
Route::get('/home/deleteUser', 'HomeController@deleteUser')->name('home.deleteUser');
