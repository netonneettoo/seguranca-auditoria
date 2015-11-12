<?php

Route::when('*', 'csrf', array('post', 'put', 'delete'));

Route::get('/', function() {
	return redirect('/packages');
});

Route::get('home', 'HomeController@index');

Route::post('packages/import', 'PackagesController@import');
Route::get('packages/export', 'PackagesController@export');
Route::resource('packages', 'PackagesController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'destroy', 'update']]);
Route::resource('rules', 'RulesController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'destroy', 'update']]);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
