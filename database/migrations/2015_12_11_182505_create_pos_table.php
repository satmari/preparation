<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePosTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		// Schema::dropIfExists('pos');

		Schema::create('pos', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('po_key', 10)->unique();
            $table->string('order_code', 30)->nullable();
            $table->string('po', 6)->nullable();			// change to 6
            $table->string('size', 5)->nullable();			// change to 5
            $table->string('style', 8)->nullable();
            $table->string('color', 4)->nullable();
            $table->string('color_desc', 30)->nullable();
            $table->string('season', 4)->nullable();
            $table->integer('total_order_qty')->nullable();
            $table->string('flash', 32)->nullable();
            $table->string('closed_po', 12)->nullable();
            $table->string('brand', 12)->nullable();
            $table->string('status', 12)->nullable();
            $table->string('type', 12)->nullable();
            $table->text('comment')->nullable();
            $table->string('delivery_date', 20)->nullable();
            $table->text('hangtag')->nullable();
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
		Schema::drop('pos');
	}

}
