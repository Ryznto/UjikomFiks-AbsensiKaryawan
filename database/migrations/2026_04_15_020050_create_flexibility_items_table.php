<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flexibility_items', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->integer('point_cost');
            $table->integer('stock_limit')->nullable(); // batas beli per bulan, null = unlimited
            $table->enum('token_type', ['late_tolerance', 'izin_tanpa_surat', 'wfh']);
            $table->integer('tolerance_minutes')->nullable(); // khusus token telat, misal 30 menit
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flexibility_items');
    }
};