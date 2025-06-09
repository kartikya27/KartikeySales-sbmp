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
        Schema::create(ORDER_ADDRESSES_TABLE, function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained(ORDER_TABLE)->cascadeOnDelete();
            $table->json('billing_address')->nullable();
            $table->json('shipping_address')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ORDER_ADDRESSES_TABLE);
    }
};
