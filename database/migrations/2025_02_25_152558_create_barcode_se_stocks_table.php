<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarcodeSeStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('barcode_se_stocks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('po_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('ponum', 10)->nullable();
            $table->string('size', 5)->nullable();	
            $table->integer('qty')->nullable();
            $table->integer('qty_to_receive')->nullable();
            $table->string('module', 20)->nullable();
            $table->string('status', 12)->nullable();
            $table->string('type', 12)->nullable();
            $table->text('comment')->nullable();
			$table->text('machine')->nullable(); 

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
		Schema::drop('barcode_se_stocks');
	}

}
