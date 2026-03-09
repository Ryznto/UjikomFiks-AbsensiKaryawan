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
       Schema::create('presensi', function (Blueprint $table) {
    $table->id();
    $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
    $table->date('tanggal');
    $table->unsignedBigInteger('shift_id')->nullable();
    $table->time('jam_masuk')->nullable();
    $table->time('jam_pulang')->nullable();
    $table->string('foto_masuk')->nullable();
    $table->string('foto_pulang')->nullable();
    $table->decimal('latitude', 10, 7)->nullable();
    $table->decimal('longitude', 10, 7)->nullable();
    $table->enum('status_absen', ['tepat_waktu', 'terlambat', 'pulang_cepat', 'alfa'])->nullable();
    $table->enum('status_pulang', ['tepat_waktu', 'pulang_cepat'])->nullable();
    $table->text('keterangan')->nullable();
    $table->timestamps();

    $table->unique(['karyawan_id', 'tanggal']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
