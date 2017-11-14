<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintBBLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('print_b_b_labels', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('komesa')->nullable();
			$table->string('bb')->nullable();
			$table->string('marker')->nullable();
			$table->string('style')->nullable();
			$table->string('variant')->nullable();
			$table->string('qty')->nullable();
			
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
		Schema::drop('print_b_b_labels');
	}

}
