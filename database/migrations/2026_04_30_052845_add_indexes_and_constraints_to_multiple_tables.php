<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // M-3: Composite index on product_carts for fast cart-item lookups
        Schema::table('product_carts', function (Blueprint $table) {
            $table->index(['user_id', 'product_id', 'variant_id'], 'product_carts_user_product_variant_idx');
        });

        // L-3: Index on users.role_id for middleware role checks
        Schema::table('users', function (Blueprint $table) {
            $table->index('role_id', 'users_role_id_idx');
        });
    }

    public function down(): void
    {
        Schema::table('product_carts', function (Blueprint $table) {
            $table->dropIndex('product_carts_user_product_variant_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_id_idx');
        });
    }
};
