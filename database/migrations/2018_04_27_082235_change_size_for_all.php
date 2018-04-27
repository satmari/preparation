<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeSizeForAll extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('barcode_stocks', function(Blueprint $table)
		{
			
            $table->string('size', 5)->nullable()->change();
            
		});

		Schema::table('pos', function(Blueprint $table)
		{
			
            $table->string('size', 5)->nullable()->change();
            
		});

		Schema::table('barcode_requests', function(Blueprint $table)
		{
			
            $table->string('size', 5)->nullable()->change();
           
		});

		Schema::table('carelabel_requests', function(Blueprint $table)
		{
			
            $table->string('size', 5)->nullable()->change();
           
		});

		Schema::table('carelabel_stocks', function(Blueprint $table)
		{
			
            $table->string('size', 5)->nullable()->change();
            
		});

		Schema::table('secondq_requests', function(Blueprint $table)
		{
			
            $table->string('size', 5)->nullable()->change();

		});


	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
