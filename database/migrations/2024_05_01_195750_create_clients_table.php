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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('second_name', 100);
            $table->string('last_name', 100);
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->integer('employees_number')->comment('Кол-во сотрудников компании');
            $table->integer('assessments_number')->comment('Кол-во оценок в подписке');
            $table->timestamp('duration')->comment('Длительность подписки');
            $table->boolean('instant_access')->comment('Выдать доступ сразу или после оплаты');
            $table->string('INN', 12)->comment('ИНН');
            $table->foreignId('client_status_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
