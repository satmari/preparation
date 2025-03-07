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
            $table->string('po_key', 15)->unique();			// change to 12 and 15
            $table->string('order_code', 30)->nullable();
            $table->string('po', 10)->nullable();			// change to 6 
            $table->string('size', 5)->nullable();			// change to 5
            $table->string('style', 8)->nullable();
            $table->string('color', 4)->nullable();
            $table->string('color_desc', 30)->nullable();
            $table->string('season', 4)->nullable();
            $table->integer('total_order_qty')->nullable();
            $table->string('flash', 32)->nullable();
            $table->string('closed_po', 12)->nullable();
            $table->string('brand', 20)->nullable();
            $table->string('status', 12)->nullable();
            $table->string('type', 12)->nullable();
            $table->text('comment')->nullable();
            $table->string('delivery_date', 20)->nullable();
            $table->text('hangtag')->nullable();
            $table->string('sap_material')->nullable(); 	// new
            $table->string('skeda')->nullable(); 	// new
            $table->string('no_lines_by_skeda')->nullable(); 	// new

            $table->string('po_new')->nullable(); 	// new

            $table->string('loc_id_su')->nullable(); 	// new
            $table->string('loc_id_ki')->nullable(); 	// new
            $table->string('loc_id_se')->nullable(); 	// new
            
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
