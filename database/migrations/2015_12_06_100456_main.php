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
            $table->string('order_code', 30)->nullable();
            $table->string('po', 5)->nullable();
            $table->string('size', 3)->nullable();
            $table->string('style', 8)->nullable();
            $table->string('color', 4)->nullable();
            $table->string('color_desc', 30)->nullable();
            $table->string('season', 4)->nullable();
            $table->integer('total_order_qty')->nullable(); 
            $table->boolean('flash')->nullable();
            $table->boolean('closed_po')->nullable();
            $table->string('status')->nullable();
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
		Schema::drop('main');
	}

}
