<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintRequestLabelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('print_request_labels', function(Blueprint $table)
		{
			$table->increments('id');
			
			$table->integer('po_id');
			$table->string('po');

			$table->string('type');
			
			$table->string('style')->nullable();
			$table->string('color')->nullable();
			$table->string('size')->nullable();
			$table->string('module')->nullable();
			$table->string('leader')->nullable();

			$table->string('comment')->nullable();
			$table->string('created')->nullable();

			$table->string('printer')->nullable();
			
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
		Schema::drop('print_request_labels');
	}

}

