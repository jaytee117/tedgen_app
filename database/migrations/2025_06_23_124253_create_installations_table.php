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
        Schema::create('installations', function (Blueprint $table) {
            $table->id();
            $table->integer('account_id');
            $table->integer('site_id');
            $table->decimal('elec_day_rate', 10, 6);
            $table->decimal('elec_night_rate', 10, 6);
            $table->decimal('elec_ccl_rate', 10, 6);
            $table->decimal('gas_rate', 10, 6);
            $table->decimal('gas_ccl_rate', 10, 6);
            $table->decimal('gas_ccl_discount', 10, 2);
            $table->decimal('elec_ccl_discount', 10, 2);
            $table->decimal('boiler_efficiency', 10, 2);
            $table->decimal('tedgen_discount', 10, 2);
            $table->decimal('tedgen_elec_day', 10, 6);
            $table->decimal('tedgen_elec_night', 10, 6);
            $table->decimal('tedgen_gas_heating', 10, 6);
            $table->decimal('calorific_value', 10, 6);
            $table->decimal('conversion_factor', 10, 6);
            $table->decimal('elec_carbon_rate', 10, 6)->nullable();
            $table->decimal('gas_carbon_rate', 10, 6)->nullable();
            $table->string('xero_id')->nullable();
            $table->integer('machine_type');
            $table->integer('machine_status');
            $table->string('machine_model')->nullable();
            $table->integer('team_id');
            $table->integer('logger_type')->nullable();
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installations');
    }
};
