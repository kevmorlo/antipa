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
            $table->integer('totalConfirmed', 10);
            $table->integer('totalDeaths', 10);
            $table->integer('totalActive', 10);
            $table->date('dateInfo');
            $table->foreignId('diseaseId')->constrained('diseases');
            $table->foreignId('localizationId')->constrained('localizations');
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
