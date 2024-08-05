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
        Schema::create('holidays_x_participants', function (Blueprint $table) {

            $table->uuid('id')->primary();

            $table->uuid('holiday_id');
            $table->uuid('participant_id');

            $table->foreign('holiday_id')->references('id')->on('holiday_plans');
            $table->foreign('participant_id')->references('id')->on('participants');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('holidays_x_participants');
    }
};
