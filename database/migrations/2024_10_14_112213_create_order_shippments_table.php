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
        
        Schema::create(ORDER_SHIPMENT_TABLE, function (Blueprint $table) {

            $table->id('id');
            $table->foreignId('order_id')->constrained(ORDER_TABLE)->onDelete('cascade');
            $table->foreignId('order_address_id')->nullable()->constrained(ORDER_ADDRESSES_TABLE)->cascadeOnDelete();
            $table->string('total_shipped_item')->nullable();
        
            $table->string('tracking_number')->nullable();
            $table->string('status')->default('ordered');
            $table->string('carrier_logo')->nullable();
            $table->string('carrier_title')->nullable();
            $table->string('tracking_link')->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamp('ready_to_dispatch_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->string('picked_up_by')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('delivered_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(ORDER_SHIPMENT_TABLE);
    }
};
