<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sequences', function (Blueprint $table) {
            $table->string('name', 64)->primary();
            $table->unsignedBigInteger('value')->default(0);
        });

        // Seed with the current max invoice number so existing orders are not re-used
        $maxInvoice = DB::table('orders')->max('invoice_number') ?? 0;
        DB::table('sequences')->insert([
            'name'  => 'invoice_number',
            'value' => (int) $maxInvoice,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('sequences');
    }
};
