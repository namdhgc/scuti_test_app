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

	Route::get('/', ['as' => 'home-page', function () {
	    
		return App::make('App\Http\Controllers\UserController')->getData();
	}]);
});

Route::group(['as' => 'post-'], function () {

	Route::post('delete-user', ['as' => 'delete-user', function () {

		return App::make('App\ttp\Controllers\UserController')->deleteData();
	}]);

	Route::post('add-user', ['as' => 'add-user', function () {

		return App::make('App\Http\Controllers\UserController')->insertData();
	}]);

	Route::post('edit-user', ['as' => 'edit-user', function () {

		return App::make('App\Http\Controllers\UserController')->updateData();
	}]);
});