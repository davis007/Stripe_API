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
		Schema::create('operate_logs', function (Blueprint $table) {
			$table->id();
			$table->string('shop_code', 4)->comment('ショップコード');
			$table->string('type', 7)->comment('web or api');
			$table->string('operate')->comment('操作情報 決済 or 顧客作成 or 顧客削除 or 返金など');
			$table->string('memo')->nullable()->comment('返金IDや顧客IDなどの保管用');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('operate_logs');
	}
};
