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
	    
	    // return view('welcome');
		$results = App::make('App\Http\Controllers\UserController')->getData();

	    return view('index')->with( 'data', $results );

	}]);
});

Route::group(['as' => 'post-'], function () {

	Route::post('delete-user', ['as' => 'delete-user', function () {

		$results = App::make('App\Http\Controllers\UserController')->deleteData();

		return json_encode( $results );
	    // return view('index')->with( 'data', $results );
	}]);
});