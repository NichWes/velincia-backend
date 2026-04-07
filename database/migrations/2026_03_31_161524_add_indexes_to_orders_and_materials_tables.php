<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->index('status');
            $table->index('created_at');
            $table->index('user_id');
            $table->index('project_id');
            $table->index('delivery_method');
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->index('is_active');
            $table->index('category');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['project_id']);
            $table->dropIndex(['delivery_method']);
        });

        Schema::table('materials', function (Blueprint $table) {
            $table->dropIndex(['is_active']);
            $table->dropIndex(['category']);
            $table->dropIndex(['name']);
        });
    }
};