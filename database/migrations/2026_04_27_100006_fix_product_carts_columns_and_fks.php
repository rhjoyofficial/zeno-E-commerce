<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_carts', function (Blueprint $table) {
            // Drop denormalized columns — color/size are already on the variant relation
            $table->dropColumn(['color', 'size']);

            // Fix FK: product deleted → cascade delete cart items (currently restrict)
            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->cascadeOnDelete()
                ->restrictOnUpdate();

            // Fix FK: user deleted → cascade delete cart items (currently restrict)
            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            // Fix FK: variant deleted → null, not cascade delete (currently cascade)
            $table->dropForeign(['variant_id']);
            $table->foreign('variant_id')
                ->references('id')->on('product_variants')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('product_carts', function (Blueprint $table) {
            $table->string('color', 200)->nullable()->after('variant_id');
            $table->string('size', 200)->nullable()->after('color');

            $table->dropForeign(['product_id']);
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->dropForeign(['user_id']);
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->dropForeign(['variant_id']);
            $table->foreign('variant_id')
                ->references('id')->on('product_variants')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }
};
