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

        Schema::create(ORDER_PAYMENT_TABLE, function (Blueprint $table) {
            $table->id();

            $table->integer('order_id')->nullable()->unsigned();

            $table->string('payment_status')->nullable();
            $table->string('payment_id')->nullable();

            $table->string('method');
            $table->string('method_title')->nullable();
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
        Schema::dropIfExists(ORDER_PAYMENT_TABLE);
    }
};
