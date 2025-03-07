<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePoTo6 extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		// Schema::table('barcode_stocks', function (Blueprint $table) {
  //   		$table->string('ponum', 7)->nullable()->change();
		// });

		// Schema::table('barcode_requests', function (Blueprint $table) {
  //   		$table->string('ponum', 7)->nullable()->change();
		// });

		// Schema::table('carelabel_requests', function (Blueprint $table) {
  //   		$table->string('ponum', 7)->nullable()->change();
		// });

		// Schema::table('carelabel_stocks', function (Blueprint $table) {
  //   		$table->string('ponum', 7)->nullable()->change();
		// });

		// Schema::table('secondq_requests', function (Blueprint $table) {
  //   		$table->string('ponum', 7)->nullable()->change();
		// });

		// Schema::table('pos', function (Blueprint $table) {
  //   		$table->string('po', 7)->nullable()->change();
		// });

		// Schema::table('pos', function (Blueprint $table) {
  //   		$table->string('po_key', 15)->change();
		// });

		// Schema::table('pos', function (Blueprint $table) {
    		// $table->string('no_lines_by_skeda')->nullable(); 	// new
    		// $table->string('po_new')->nullable(); 	// new
    		// $table->string('loc_id_su')->nullable(); 	// new
      		// $table->string('loc_id_ki')->nullable(); 	// new
      		// $table->string('loc_id_se')->nullable(); 	// new
		// });

		// Schema::table('leftovers', function (Blueprint $table) {
  //   		$table->string('place')->nullable();
		// });
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
