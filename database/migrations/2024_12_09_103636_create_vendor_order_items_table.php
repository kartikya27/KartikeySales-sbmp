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

        Schema::create(VENDOR_ORDER_ITEM_TABLE, function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_order_id')->nullable()->constrained(VENDOR_ORDER_TABLE);
            $table->foreignId('order_item_id')->nullable()->constrained(ORDER_ITEM_TABLE);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(VENDOR_ORDER_ITEM_TABLE);
    }
};
