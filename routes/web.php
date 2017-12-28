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




Route::group(['as' => 'get-'], function () {

	Route::get('/', 'UserController@getData')->name('home-page');
});

Route::group(['prefix' => 'user'], function() {

	Route::post('delete', 'UserController@deleteData')->name('delete-user');
	Route::post('add', 'UserController@insertData')->name('add-user');
	Route::post('edit', 'UserController@updateData')->name('edit-user');
});