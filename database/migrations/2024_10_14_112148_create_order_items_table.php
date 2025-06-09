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

        Schema::create(ORDER_ITEM_TABLE, function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained(ORDER_TABLE)->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('sku')->nullable();
            $table->string('type')->nullable();
            $table->string('name')->nullable(); //product name
            $table->integer('product_id')->unsigned()->nullable();
            $table->string('product_type')->nullable();
            $table->string('parent_id')->nullable();

            $table->decimal('price', 12, 4)->default(0);

            $table->decimal('total', 12, 4)->default(0);

            $table->integer('qty_ordered')->default(0)->nullable();

            $table->integer('qty_canceled')->default(0)->nullable();

            $table->decimal('shipping_amount', 12, 4)->default(0);
            $table->decimal('tax_amount', 12, 4)->default(0)->nullable();
            $table->decimal('discount_amount', 12, 4)->default(0)->nullable();

            $table->decimal('amount_refunded', 12, 4)->default(0);

            $table->json('additional')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ORDER_ITEM_TABLE);
    }
};
