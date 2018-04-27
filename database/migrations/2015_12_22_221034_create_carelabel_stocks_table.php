<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarelabelStocksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('carelabel_stocks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('po_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('ponum', 6)->nullable(); // change to 6
            $table->string('size', 5)->nullable();	// change to 5
            $table->integer('qty')->nullable();
            $table->string('module', 20)->nullable();
            $table->string('status', 12)->nullable();
            $table->string('type', 12)->nullable();
            $table->text('comment')->nullable();
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
		Schema::drop('carelabel_stocks');
	}

}
