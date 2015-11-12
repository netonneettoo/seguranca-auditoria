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

Route::get('test/{hash}', function($hash) {
    if ($hash == 'rules') {

//        $data = [
//            'priority'      => 12,
//            'name'          => 'required | unique | ',
//            'source'        => '192.168.234.213',
//            'destination'   => '123.210.90.119',
//            'direction'     => \App\Rule::DIRECTION_IN,
//            'protocol'      => 'icmp',
//            'start_port'    => '1234',
//            'end_port'      => '4321',
//            'action'        => \App\Rule::ACTION_ALLOW,
//            'content'       => 'Soube que me amava, entendi que eu ja nao podia re',
//        ];
//
//        $obj = new \App\Rule();
//        $obj = $obj->store($data);
//        $obj->save();


        //$data = preg_match($regex, "123.123.123.123");

        return '';
    }

    return abort(405, 'Method not allowed.');
});