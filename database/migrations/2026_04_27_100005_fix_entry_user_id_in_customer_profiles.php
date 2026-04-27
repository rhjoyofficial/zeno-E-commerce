<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            // Change 32-bit unsignedInteger to 64-bit unsignedBigInteger to match users.id
            $table->unsignedBigInteger('entry_user_id')->nullable()->change();
            // Add the missing FK constraint
            $table->foreign('entry_user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->dropForeign(['entry_user_id']);
            $table->unsignedInteger('entry_user_id')->nullable()->change();
        });
    }
};
