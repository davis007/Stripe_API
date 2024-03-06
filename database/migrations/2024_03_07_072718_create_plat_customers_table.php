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
		Schema::create('plat_customers', function (Blueprint $table) {
			$table->id();
			$table->string('customer_id')->comment('8桁の顧客コード');
			$table->string('plat_name')->comment('PlatForm名称');
			$table->string('plat_id')->comment('PlatFormから割り振られたカスタマーID');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('plat_customers');
	}
};
