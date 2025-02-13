<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodeStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('barcode_stocks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('po_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('ponum', 10)->nullable();	// change to 6 adn 10
            $table->string('size', 5)->nullable();	// change to 5
            $table->integer('qty')->nullable();
            $table->string('module', 20)->nullable();
            $table->string('status', 12)->nullable();
            $table->string('type', 12)->nullable();
            $table->text('comment')->nullable();

			$table->text('machine')->nullable(); // added 2023.05.22

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
		Schema::drop('barcode_stocks');
	}

}
