<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('customers', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('customer_id')->unique()->nullable()->comment('顧客ID');
			$table->string('shopCode')->comment('店舗コード');
			$table->string('email')->unique()->comment('メアド');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('customers');
	}
};
