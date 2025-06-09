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

        Schema::create(VENDOR_ORDER_TABLE, function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained(ORDER_TABLE)->cascadeOnDelete();
            $table->string('vendor_order_number')->nullable();
            $table->foreignId('seller_id')->nullable()->constrained(SELLER_TABLE)->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(VENDOR_ORDER_TABLE);
    }
};
