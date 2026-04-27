<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->renameColumn('cus_name', 'name');
            $table->renameColumn('cus_address', 'address');
            $table->renameColumn('cus_city', 'city');
            $table->renameColumn('cus_state', 'state');
            $table->renameColumn('cus_postcode', 'postal_code');
            $table->renameColumn('cus_phone', 'phone');
        });
    }

    public function down(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->renameColumn('name', 'cus_name');
            $table->renameColumn('address', 'cus_address');
            $table->renameColumn('city', 'cus_city');
            $table->renameColumn('state', 'cus_state');
            $table->renameColumn('postal_code', 'cus_postcode');
            $table->renameColumn('phone', 'cus_phone');
        });
    }
};
