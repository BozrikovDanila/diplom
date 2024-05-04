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
        Schema::create('risk_matrix_risk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_matrix_id')->constrained()->cascadeOnDelete();
            $table->foreignId('risk_id')->constrained()->cascadeOnDelete();
            $table->unique(['risk_matrix_id', 'risk_id']);
            $table->json('probabilities')->comment('Цвет вероятности риска в зависимости от score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_matrix_risk');
    }
};
