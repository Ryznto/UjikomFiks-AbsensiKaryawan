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
          Schema::create('karyawan', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->string('nama');
        $table->foreignId('divisi_id')->constrained('divisi');
        $table->foreignId('jabatan_id')->constrained('jabatan');
        $table->foreignId('shift_id')->constrained('shift');
        $table->string('no_hp')->nullable();
        $table->string('foto')->nullable();
        $table->boolean('status_aktif')->default(true);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
