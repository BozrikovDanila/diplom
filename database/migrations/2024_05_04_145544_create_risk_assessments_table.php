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
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->timestamp('completion_date')->comment('Дата результата оценки');
            $table->unsignedSmallInteger('total_score')->comment('Общее кол-во очков рисков');
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assessment_status_id')->constrained();
            $table->foreignId('risk_matrix_id')->constrained();
            $table->string('org_name')->comment('Название оцениваемой организации');
            $table->string('INN', 12)->comment('ИНН оцениваемой организации');
            $table->json('selected_risks')
                ->comment('Список выбранных в оценку контрагента рисков');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
