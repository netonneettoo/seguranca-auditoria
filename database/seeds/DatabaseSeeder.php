<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$user = new App\User();
		$user->name = 'admin';
		$user->email = 'admin@admin.com';
		$user->password = bcrypt('default');
		$user->save();

		// $this->call('UserTableSeeder');
	}

}
