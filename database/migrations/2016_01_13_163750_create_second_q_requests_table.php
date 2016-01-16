<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSecondQRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('secondq_requests', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('po_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('ponum', 5)->nullable();
            $table->string('size', 3)->nullable();
            $table->integer('qty')->nullable();
            $table->string('module', 20)->nullable();
            $table->string('leader', 30)->nullable();
            $table->string('status', 12)->nullable();
            $table->string('type', 12)->nullable();
            $table->text('comment')->nullable();
            $table->string('style',12)->nullable();
            $table->string('color',12)->nullable();
            $table->string('materiale',12)->nullable();
            $table->string('tg2', 12)->nullable();
            $table->string('desc', 32)->nullable();
            $table->string('ccc', 12)->nullable();
            $table->string('cd', 32)->nullable();
            $table->string('barcode', 32)->nullable();
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
		Schema::drop('second_q_requests');
	}

}
