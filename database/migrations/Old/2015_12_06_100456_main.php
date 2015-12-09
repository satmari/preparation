<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Main extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		 Schema::create('main', function (Blueprint $table) {
            $table->increments('id');
            $table->string('po_size', 10)->unique();
            $table->string('order_code', 30);
            $table->string('po', 5);
            $table->string('size', 3);
            $table->string('style', 6);
            $table->string('color', 4);
            $table->string('color_desc', 30);
            $table->string('season', 4);
            $table->integer('total_order_qty'); 
            $table->boolean('flash');
            $table->boolean('closed_po');
            $table->string('status');
            $table->text('comment');
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
		Schema::drop('main');
	}

}
