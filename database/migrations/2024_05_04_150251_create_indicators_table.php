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
        Schema::create('indicators', function (Blueprint $table) {
            $table->id();
            $table->string('question')->comment('Вопрос');
            $table->string('indicator_key', 45)->unique()->comment('Уникальный ключ для формул');
            $table->string('formula')->comment('Формула для расчета');
            $table->json('indicator_value')->comment('Значение (может быть множеств)');
            $table->foreignId('indicator_type_id')->constrained();
            $table->foreignId('competency_id')->constrained();
            $table->foreignId('data_source_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indicators');
    }
};
