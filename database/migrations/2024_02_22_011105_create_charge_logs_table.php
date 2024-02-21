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
		Schema::create('charge_logs', function (Blueprint $table) {
			$table->id();
			$table->string('charge_id')->comment('決済ID');
			$table->string('status')->comment('失敗と成功');
			$table->string('amount')->comment('金額');
			$table->string('payment_code')->comment('決済コード (店舗Code - 顧客ID - unixtime)');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('charge_logs');
	}
};
