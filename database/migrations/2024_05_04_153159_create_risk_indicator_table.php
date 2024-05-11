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
        Schema::create('risk_indicator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('indicator_id')->constrained()->cascadeOnDelete();
            $table->foreignId('risk_id')->constrained()->cascadeOnDelete();
            $table->unique(['indicator_id', 'risk_id']);
            $table->json('score')->nullable()->comment('Влияние значения индикатора на риск (от 0 до 4)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_indicator');
    }
};
