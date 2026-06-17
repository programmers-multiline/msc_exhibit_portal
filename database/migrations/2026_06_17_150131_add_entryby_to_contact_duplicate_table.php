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
        Schema::table('contact_duplicate', function (Blueprint $table) {
            //
             $table->string('entry_by')->nullable()->after('id');
        });
         Schema::table('contacts', function (Blueprint $table) {
            //
             $table->string('entry_by')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contact_duplicate', function (Blueprint $table) {
            //
        });
    }
};
