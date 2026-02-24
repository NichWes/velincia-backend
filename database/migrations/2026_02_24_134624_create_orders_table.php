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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('order_code')->unique();
            $table->string('order_type')->default('partial'); // partial/full
            $table->string('status')->default('draft'); 
            // draft/waiting_admin/waiting_payment/paid/processing/shipped/ready_pickup/completed/cancelled

            $table->string('delivery_method')->default('pickup'); // pickup/delivery
            $table->text('delivery_address')->nullable();

            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping_fee', 15, 2)->nullable();
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
