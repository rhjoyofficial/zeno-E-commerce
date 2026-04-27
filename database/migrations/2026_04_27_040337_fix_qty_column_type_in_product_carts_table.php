<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: add a correctly-typed column alongside the existing one
        Schema::table('product_carts', function (Blueprint $table) {
            $table->unsignedInteger('qty_int')->default(1)->after('qty');
        });

        // Step 2: copy existing data, casting the string value to integer
        DB::statement('UPDATE product_carts SET qty_int = CAST(qty AS UNSIGNED)');

        // Step 3: drop the old string column and rename the new one
        Schema::table('product_carts', function (Blueprint $table) {
            $table->dropColumn('qty');
        });

        Schema::table('product_carts', function (Blueprint $table) {
            $table->renameColumn('qty_int', 'qty');
        });
    }

    public function down(): void
    {
        // Reverse: restore qty as string to match the original migration
        Schema::table('product_carts', function (Blueprint $table) {
            $table->string('qty_str', 200)->default('1')->after('qty');
        });

        DB::statement('UPDATE product_carts SET qty_str = CAST(qty AS CHAR)');

        Schema::table('product_carts', function (Blueprint $table) {
            $table->dropColumn('qty');
        });

        Schema::table('product_carts', function (Blueprint $table) {
            $table->renameColumn('qty_str', 'qty');
        });
    }
};
