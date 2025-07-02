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
        Schema::create('meter_reading', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id')->nullable();
            $table->integer('installation_id')->nullable();
            $table->integer('dataline_id')->nullable();
            $table->timestamp('reading_date');
            $table->integer('reading_type');
            $table->string('meter_number')->nullable();
            $table->string('meter_reference')->nullable();
            $table->string('unit')->nullable();
            $table->json('hh_data');
            $table->float('total');
            $table->integer('op_count')->nullable();
            $table->integer('online')->nullable();
            $table->integer('online_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meter_reading');
    }
};
