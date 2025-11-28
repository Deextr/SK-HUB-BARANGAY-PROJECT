<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceArchivesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('service_archives', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->unsignedBigInteger('service_id')->index();
			$table->unsignedSmallInteger('units_archived');
			$table->unsignedSmallInteger('capacity_before');
			$table->unsignedSmallInteger('capacity_after');
			$table->text('reason')->collation('utf8mb4_unicode_ci');
			$table->unsignedInteger('reservations_cancelled')->default(0);
			$table->longText('cancelled_reservation_ids')->nullable()->collation('utf8mb4_bin');
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
		Schema::dropIfExists('service_archives');
	}
}

