<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->string('name');
            $table->string('sku');
            $table->text('description')->nullable();
            $table->string('variant_size')->nullable();
            $table->string('variant_color')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('original_price', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->unsignedInteger('quantity');
            $table->unsignedInteger('quantity_shipped')->default(0);
            $table->unsignedInteger('quantity_refunded')->default(0);
            $table->unsignedInteger('quantity_cancelled')->default(0);
            $table->decimal('row_total', 12, 2);
            $table->decimal('row_total_incl_tax', 12, 2);
            $table->decimal('weight', 10, 2)->nullable();
            $table->decimal('volume', 10, 2)->nullable();
            $table->enum('fulfillment_status', [
                'unfulfilled',
                'partially_fulfilled',
                'fulfilled',
                'returned',
                'cancelled'
            ])->default('unfulfilled');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index('order_id');
            $table->index('product_id');
            $table->index('sku');
            $table->index('fulfillment_status');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
