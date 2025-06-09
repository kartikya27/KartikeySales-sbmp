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

        Schema::create(ORDER_SHIPMENT_ITEM_TABLE, function (Blueprint $table) {
            $table->id();

            $table->foreignId('shipment_id')->constrained(ORDER_SHIPMENT_TABLE)->cascadeOnDelete();
            $table->foreignId('order_item_id')->nullable()->constrained(ORDER_ITEM_TABLE)->onDelete('set null');
            $table->foreignId('product_id')->nullable()->constrained(PRODUCT_TABLE)->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->integer('qty_sent')->nullable();
            $table->integer('item_status')->nullable();
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
        Schema::dropIfExists(ORDER_SHIPMENT_ITEM_TABLE);
    }
};
