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
        Schema::create('reportcases', function (Blueprint $table) {
            $table->id();
            $table->integer('totalConfirmed');
            $table->integer('totalDeaths');
            $table->integer('totalActive');
            $table->date('dateInfo');
            $table->foreignId('diseaseId');
            $table->foreignId('localizationId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportcases');
    }
};
