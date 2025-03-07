<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrepLocationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('prep_locations', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('location')->unique(); 
			$table->string('location_desc')->nullable(); 
			$table->string('location_plant'); 

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
		Schema::drop('prep_locations');
	}

}
