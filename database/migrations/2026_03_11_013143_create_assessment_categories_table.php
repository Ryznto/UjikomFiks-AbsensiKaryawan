<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('Employee');
            $table->integer('max_score')->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        DB::table('assessment_categories')->insert([
            ['name' => 'Kedisiplinan',   'description' => 'Ketepatan waktu hadir dan pulang, kepatuhan terhadap peraturan', 'type' => 'Employee', 'max_score' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kerja Sama',     'description' => 'Kemampuan bekerja dalam tim dan berkolaborasi',                  'type' => 'Employee', 'max_score' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tanggung Jawab', 'description' => 'Menyelesaikan tugas tepat waktu dan bertanggung jawab',          'type' => 'Employee', 'max_score' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Inisiatif',      'description' => 'Kemampuan mengambil inisiatif dan inovasi dalam pekerjaan',      'type' => 'Employee', 'max_score' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Komunikasi',     'description' => 'Kemampuan berkomunikasi dengan baik secara lisan maupun tulisan', 'type' => 'Employee', 'max_score' => 5, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_categories');
    }
};