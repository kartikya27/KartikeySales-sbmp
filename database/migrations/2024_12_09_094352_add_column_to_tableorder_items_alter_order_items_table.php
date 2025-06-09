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
        Schema::table(ORDER_ITEM_TABLE, function (Blueprint $table) {
            $table->enum('status', [
                'pending',             // Item is added to the order, but no action has been taken yet.
                'processing',          // Item is being prepared (e.g., packaging).
                'shipped',             // Item has been shipped.
                'delivered',           // Item has been delivered to the customer.
                'cancelled',           // Item was cancelled (either by the customer or vendor).
                'return_requested',    // Customer has requested a return for this item.
                'return_in_process',   // Return process for this item is ongoing (e.g., pickup scheduled).
                'returned',            // Item has been returned.
                'refunded',            // Item has been refunded.
                'rejected',            // Item is rejected by the vendor/admin.
                'failed',              // System failure or issue with the item.
            ])->default('pending');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(ORDER_ITEM_TABLE, function (Blueprint $table) {
            $table->enum('status', [
                'pending',             // Item is added to the order, but no action has been taken yet.
                'processing',          // Item is being prepared (e.g., packaging).
                'shipped',             // Item has been shipped.
                'delivered',           // Item has been delivered to the customer.
                'cancelled',           // Item was cancelled (either by the customer or vendor).
                'return_requested',    // Customer has requested a return for this item.
                'return_in_process',   // Return process for this item is ongoing (e.g., pickup scheduled).
                'returned',            // Item has been returned.
                'refunded',            // Item has been refunded.
                'rejected',            // Item is rejected by the vendor/admin.
                'failed',              // System failure or issue with the item.
            ])->default('pending');

        });
    }
};
