<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::post('packages/import', 'PackagesController@import');
Route::get('packages/export', 'PackagesController@export');
Route::post('packages/{id}/destroy', 'PackagesController@destroy');
Route::post('packages/{id}', 'PackagesController@update');
Route::resource('packages', 'PackagesController', ['only' => ['index', 'create', 'store', 'show', 'edit']]);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
