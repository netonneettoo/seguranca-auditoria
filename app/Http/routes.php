<?php

//Route::when('*', 'csrf', array('post', 'put', 'delete'));

Route::get('/', function() {
	return redirect('/packages');
});

Route::get('home', 'HomeController@index');

Route::post('packages/import', 'PackagesController@import');
Route::get('packages/export', 'PackagesController@export');
Route::resource('packages', 'PackagesController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'destroy', 'update']]);

Route::resource('imported-packages', 'ImportedPackagesController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'destroy', 'update']]);

Route::post('rules/sortable', 'RulesController@sortable');
Route::post('rules/import', 'RulesController@import');
Route::resource('rules', 'RulesController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'destroy', 'update']]);

Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

Route::get('/test', function() {

    return \App\ImportedPackage::all();

});