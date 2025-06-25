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
        Schema::create('dataline', function (Blueprint $table) {
            $table->id();
            $table->integer('installation_id');
            $table->integer('data_line_type');
            $table->string('line_reference')->nullable();
            $table->integer('x420_line_assignment')->nullable(); 
            $table->string('xero_account_code')->nullable();       
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dataline');
    }
};
