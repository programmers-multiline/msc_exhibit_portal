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
        Schema::table('attendees_list', function (Blueprint $table) {
            //
             $table->string('participant_id')->nullable()->after('exhibit_name');
             $table->string('agent_company_id')->nullable()->after('encoded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendees_list', function (Blueprint $table) {
            //
        });
    }
};
