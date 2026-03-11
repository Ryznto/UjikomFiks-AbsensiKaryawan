<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');      // Admin
            $table->foreignId('evaluatee_id')->constrained('karyawan')->onDelete('cascade');   // Karyawan
            $table->date('assessment_date');
            $table->string('period');
            $table->decimal('total_score', 5, 2)->default(0);
            $table->decimal('average_score', 4, 2)->default(0);
            $table->text('general_notes')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('submitted');
            $table->timestamps();

            $table->unique(['evaluator_id', 'evaluatee_id', 'period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};