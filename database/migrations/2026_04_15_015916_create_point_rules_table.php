<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name');
            $table->enum('condition_type', ['check_in', 'late_minutes', 'alfa']);
            $table->enum('condition_operator', ['<', '>', 'BETWEEN']);
            $table->string('condition_value'); // bisa jam "06:30:00" atau menit "15"
            $table->string('condition_value_max')->nullable(); // untuk BETWEEN
            $table->integer('point_modifier'); // +5 atau -3
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_rules');
    }
};