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
            $table->decimal('total_incl_tax')->nullable()->after('tax_amount');
            $table->decimal('sub_total')->nullable()->after('amount_refunded');
            $table->decimal('grand_total')->nullable()->after('sub_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(ORDER_ITEM_TABLE, function (Blueprint $table) {
            // Add your reverse alter table code here
        });
    }
};
