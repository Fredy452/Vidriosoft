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
        Schema::table('users', function (Blueprint $table) {
            // remove company_name and tax_id columns from users table
            $table->dropColumn(['company_name', 'tax_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // add company_name and tax_id columns back to users table
            $table->string('company_name')->nullable()->after('address');
            $table->string('tax_id')->nullable()->after('company_name');
        });
    }
};
