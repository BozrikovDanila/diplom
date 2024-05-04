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
        Schema::create('risk_assessment_messages', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->foreignId('user_id')->constrained();
            $table->foreignId('risk_assessment_id')->constrained()->cascadeOnDelete();
            $table->json('attached_files')->comment('Вложения');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessment_messages');
    }
};
