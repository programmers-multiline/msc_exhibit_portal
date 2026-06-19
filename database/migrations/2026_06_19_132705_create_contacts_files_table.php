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
        Schema::create('contacts_files', function (Blueprint $table) {
            $table->id();
             $table->unsignedBigInteger('company_id');
            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();

            $table->unsignedBigInteger('uploaded_by');
            $table->timestamp('uploaded_at')->nullable();

            $table->timestamps();

            $table->foreign('company_id')
                ->references('id')
                ->on('company_list')
                ->onDelete('cascade');
                });
           
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts_files');
    }
};
