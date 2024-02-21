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
		Schema::create('cards', function (Blueprint $table) {
			$table->string('customer_id')->comment('Stripeの顧客ID');
			$table->string('card_id')->comment('StripeのカードID');
			$table->string('brand')->comment('カードブランド');
			$table->string('last4')->comment('カード番号');
			$table->string('exp_month')->comment('有効期限(月)');
			$table->string('exp_year')->comment('有効期限(年)');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('cards');
	}
};
