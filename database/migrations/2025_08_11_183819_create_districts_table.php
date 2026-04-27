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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('division_id')->constrained('divisions')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->foreignId('country_id')->nullable()->after('cus_country')->constrained('countries')->nullOnDelete();
            $table->foreignId('division_id')->nullable()->after('country_id')->constrained('divisions')->nullOnDelete();
            $table->foreignId('district_id')->nullable()->after('division_id')->constrained('districts')->nullOnDelete();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropForeign(['division_id']);
            $table->dropForeign(['district_id']);
            $table->dropColumn(['country_id', 'division_id', 'district_id']);
        });

        Schema::dropIfExists('districts');
    }
};
