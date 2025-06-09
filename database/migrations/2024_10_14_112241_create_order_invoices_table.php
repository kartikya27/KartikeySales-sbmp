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

        Schema::create(ORDER_INVOICE_TABLE, function (Blueprint $table) {
            $table->id();

            $table->string('state')->nullable();
            $table->boolean('email_sent')->default(0);
            $table->integer('total_qty')->nullable();
            $table->string('base_currency_code')->nullable(); // product currency code //
            $table->string('channel_currency_code')->nullable();
            $table->string('order_currency_code')->nullable();
            $table->decimal('sub_total', 12, 4)->default(0)->nullable();

            $table->decimal('grand_total', 12, 4)->default(0)->nullable();

            $table->decimal('shipping_amount', 12, 4)->default(0)->nullable();

            $table->decimal('tax_amount', 12, 4)->default(0)->nullable();

            $table->decimal('discount_amount', 12, 4)->default(0)->nullable();

            $table->integer('order_id')->unsigned()->nullable();

            $table->string('transaction_id')->nullable();

            $table->integer('reminders')->default(0);

            $table->timestamp('next_reminder_at')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ORDER_INVOICE_TABLE);
    }
};
