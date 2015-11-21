<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportedPackagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('imported_packages', function(Blueprint $table)
		{
			$table->increments('id');

            $table->text('source');
            $table->text('destination');
            $table->text('port');
            $table->text('protocol');
            $table->text('package_id');
            $table->text('data');

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
		Schema::drop('imported_packages');
	}

}
