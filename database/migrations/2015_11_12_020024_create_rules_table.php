<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rules', function(Blueprint $table)
		{
			$table->increments('id');

            $table->string('priority', 2); // 1 e 99
            $table->string('name', 20);
            $table->string('source', 15);
            $table->string('destination', 15);
            $table->string('direction');
            $table->string('protocol', 4);
            $table->string('start_port', 5);
            $table->string('end_port', 5)->nullable();
            $table->string('action');
            $table->string('content', 30);

            $table->unique(array('name'));

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
		Schema::drop('rules');
	}

}
