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
        Schema::create('weatherreadings', function (Blueprint $table) {
            $table->id();
            $table->integer('site_id');
            $table->timestamp('reading_date');
            $table->decimal('temp', 5, 2);
            $table->integer('pressure');
            $table->integer('humidity');
            $table->decimal('wind_speed', 5, 2);
            $table->integer('cloud');
            $table->timestamp('sunrise');
            $table->timestamp('sunset');
            $table->string('icon');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weatherreadings');
    }
};
