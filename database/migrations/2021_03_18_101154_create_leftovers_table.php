<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeftoversTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('leftovers', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('material');
			$table->string('sku');
			$table->float('price');
			$table->integer('qty');
			$table->string('status');
			$table->string('location')->nullable();
			$table->string('place')->nullable();

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
		Schema::drop('leftovers');
	}

}
