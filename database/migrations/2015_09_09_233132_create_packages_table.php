<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('packages', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('source', 15);
			$table->string('destination', 15);
			$table->string('port', 4);
			$table->enum('protocol', array('tcp', 'udp', 'icmp'));
			$table->string('package_id', 4);
			$table->string('data', 50);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('packages');
	}

}
