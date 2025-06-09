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

        Schema::create(ORDER_TABLE, function (Blueprint $table) {
            $table->id();

            $table->string('order_number');

            $table->enum('status', [
                'pending',
                'payment_failed',
                'on_hold',
                'payment_received',
                'confirmed',
                'processing',
                'shipped',
                'out_for_delivery',
                'delivered',
                'return_requested',
                'return_in_process',
                'returned',
                'refunded',
                'cancelled',
                'failed',
                'rejected',
                'cod_pending_verification',
                'cod_confirmed'
            ])->default('pending');

            $table->string('channel')->default(1);
            $table->string('country_origin')->default('de');

            $table->foreignId('user_id')->constrained(USER_TABLE)->cascadeOnUpdate();

            $table->string('customer_email')->nullable();

            $table->string('customer_name')->nullable();

            $table->integer('total_item_count')->nullable();

            $table->integer('total_qty_ordered')->nullable();

            $table->string('base_currency_code')->default('EUR'); //* channel currency as default

            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('grand_total', 12, 4)->default(0)->nullable();

            $table->string('coupon_code')->nullable(); //* code
            $table->decimal('discount_percent', 12, 4)->default(0)->nullable(); //* Discount in percantage
            $table->decimal('discount_amount', 12, 4)->default(0)->nullable(); //* discounted amount

            $table->decimal('base_tax_amount', 12, 4)->default(0)->nullable(); // before discount tax amount
            $table->decimal('tax_amount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_amount_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('tax_amount', 12, 4)->default(0)->nullable(); //* total taxed amount

            $table->decimal('base_shipping_amount', 12, 4)->default(0)->nullable(); //* before shipping tax applied or discount applied
            $table->decimal('shipping_discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_refunded', 12, 4)->default(0)->nullable();
            $table->decimal('shipping_amount', 12, 4)->default(0)->nullable(); //* final shipping amount
            $table->decimal('grand_total_refunded', 12, 4)->default(0)->nullable();

            $table->decimal('total_refunded', 12, 4)->default(0)->nullable();


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ORDER_TABLE);
    }
};
