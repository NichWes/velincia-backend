<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_token')->nullable()->after('applied_to_project_at');
            $table->text('payment_url')->nullable()->after('payment_token');
            $table->string('payment_type')->nullable()->after('payment_url');
            $table->string('transaction_status')->nullable()->after('payment_type');
            $table->string('fraud_status')->nullable()->after('transaction_status');
            $table->timestamp('paid_at')->nullable()->after('fraud_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_token',
                'payment_url',
                'payment_type',
                'transaction_status',
                'fraud_status',
                'paid_at',
            ]);
        });
    }
};