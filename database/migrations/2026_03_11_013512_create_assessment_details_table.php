<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Buat tabel statements
        Schema::create('assessment_statements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('assessment_categories')->onDelete('cascade');
            $table->string('statement');
            $table->integer('order')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed pernyataan default
        $categories = DB::table('assessment_categories')->get();
        foreach ($categories as $cat) {
            $statements = [];
            switch ($cat->name) {
                case 'Kedisiplinan':
                    $statements = [
                        'Hadir tepat waktu setiap hari kerja',
                        'Tidak meninggalkan tempat kerja tanpa izin',
                        'Mematuhi peraturan dan tata tertib perusahaan',
                        'Berpakaian sesuai dengan aturan perusahaan',
                        'Menyelesaikan tugas sesuai dengan deadline',
                    ];
                    break;
                case 'Kerja Sama':
                    $statements = [
                        'Mampu bekerja sama dengan rekan satu tim',
                        'Bersedia membantu rekan kerja yang membutuhkan bantuan',
                        'Menghargai pendapat dan ide orang lain',
                        'Berkontribusi aktif dalam pekerjaan tim',
                        'Menjaga hubungan baik dengan seluruh rekan kerja',
                    ];
                    break;
                case 'Tanggung Jawab':
                    $statements = [
                        'Menyelesaikan pekerjaan yang diberikan dengan baik',
                        'Berani mengakui kesalahan dan memperbaikinya',
                        'Tidak melimpahkan kesalahan kepada orang lain',
                        'Menjaga aset dan fasilitas perusahaan dengan baik',
                        'Melaporkan hasil pekerjaan secara jujur dan tepat waktu',
                    ];
                    break;
                case 'Inisiatif':
                    $statements = [
                        'Mampu mengidentifikasi masalah dan mencari solusi',
                        'Berani mengusulkan ide-ide baru yang inovatif',
                        'Tidak menunggu perintah untuk mengerjakan tugas',
                        'Aktif mencari cara untuk meningkatkan kualitas kerja',
                        'Mampu beradaptasi dengan perubahan dan tantangan baru',
                    ];
                    break;
                case 'Komunikasi':
                    $statements = [
                        'Mampu menyampaikan informasi dengan jelas dan tepat',
                        'Mendengarkan dengan baik saat orang lain berbicara',
                        'Mampu membuat laporan tertulis dengan baik',
                        'Berkomunikasi dengan sopan dan profesional',
                        'Mampu mempresentasikan ide dengan percaya diri',
                    ];
                    break;
            }
            foreach ($statements as $i => $stmt) {
                DB::table('assessment_statements')->insert([
                    'category_id' => $cat->id,
                    'statement'   => $stmt,
                    'order'       => $i + 1,
                    'is_active'   => true,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        // Buat tabel details
        Schema::create('assessment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->foreignId('statement_id')->constrained('assessment_statements')->onDelete('cascade');
            $table->decimal('score', 4, 2)->default(0);
            $table->timestamps();

            $table->unique(['assessment_id', 'statement_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_details');
        Schema::dropIfExists('assessment_statements');
    }
};