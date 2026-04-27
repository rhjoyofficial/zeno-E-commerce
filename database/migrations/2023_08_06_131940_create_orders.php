<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->string('order_number')->unique();
            $table->bigInteger('invoice_number')->unsigned()->nullable()->unique();
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded',
                'partially_refunded',
                'on_hold'
            ])->default('pending')->index();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('shipping_amount', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->decimal('total_paid', 12, 2)->default(0);
            $table->decimal('total_refunded', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->enum('payment_status', ['pending', 'paid', 'partially_paid', 'refunded', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->text('payment_notes')->nullable();
            $table->unsignedBigInteger('shipping_address_id')->nullable();
            $table->string('shipping_method')->nullable();
            $table->decimal('shipping_weight', 10, 2)->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('tracking_url')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('guest_session_id')->nullable();
            $table->string('customer_email')->index()->nullable();
            $table->string('customer_phone')->index()->nullable();
            $table->string('customer_ip')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('processing_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('payment_status');
            $table->index('created_at');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('restrict');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses')->onDelete('set null');
        });

        Schema::create('sequences', function (Blueprint $table) {
            $table->string('name', 64)->primary();
            $table->unsignedBigInteger('value')->default(0);
        });

        DB::table('sequences')->insert([
            'name' => 'invoice_number',
            'value' => 0,
        ]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sequences');
        Schema::dropIfExists('orders');
    }
};
