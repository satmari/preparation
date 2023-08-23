<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePoKey extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//

		// Schema::table('pos', function (Blueprint $table) {
  //   		// $table->string('po_key')->unique()->change();
  //   		// $table->string('skeda')->nullable(); 	// new
		// });

		Schema::table('barcode_stocks', function (Blueprint $table) {
    		
    		// $table->text('machine')->nullable(); // added 2023.05.22 
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
