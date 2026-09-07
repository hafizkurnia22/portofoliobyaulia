<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_materis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_kategori_soal_id')->constrained('tryout_kategori_soals')->cascadeOnDelete();
            $table->string('judul', 150);
            $table->string('ringkasan', 500)->nullable();
            $table->longText('isi_materi');
            $table->enum('status', ['aktif', 'draft'])->default('aktif');
            $table->timestamps();
        });

        $now = now();
        $categories = DB::table('tryout_kategori_soals')->pluck('id', 'kode');
        $materials = [
            'TWK' => [
                'judul' => 'Pilar Kebangsaan dan Nasionalisme',
                'ringkasan' => 'Penguatan pemahaman Pancasila, UUD 1945, NKRI, Bhinneka Tunggal Ika, dan sikap nasionalisme.',
                'isi_materi' => "TWK mengukur wawasan kebangsaan, komitmen terhadap Pancasila, UUD 1945, NKRI, dan Bhinneka Tunggal Ika.\n\nFokus belajar TWK meliputi sejarah perjuangan bangsa, nilai dasar Pancasila, sistem ketatanegaraan, bela negara, integritas, dan penggunaan bahasa Indonesia yang baik.\n\nSaat mengerjakan soal TWK, baca kata kunci pada pertanyaan. Jika soal menanyakan sila Pancasila, cocokkan nilai inti seperti ketuhanan, kemanusiaan, persatuan, musyawarah, atau keadilan sosial.",
            ],
            'TIU' => [
                'judul' => 'Kemampuan Verbal, Numerik, dan Logika',
                'ringkasan' => 'Latihan kemampuan berpikir runtut melalui sinonim, antonim, deret angka, perbandingan, dan penalaran.',
                'isi_materi' => "TIU mengukur kemampuan berpikir logis, analitis, numerik, dan verbal.\n\nMateri utama TIU mencakup sinonim, antonim, analogi, silogisme, penalaran analitis, aritmetika, deret angka, perbandingan, serta pola gambar.\n\nStrategi TIU adalah mengenali pola lebih dulu. Untuk soal numerik, tulis hubungan antar angka. Untuk soal verbal, pahami konteks kata, bukan hanya hafalan arti.",
            ],
            'TKP' => [
                'judul' => 'Karakter Pelayanan dan Profesionalisme',
                'ringkasan' => 'Pembelajaran respons kerja yang paling tepat pada situasi pelayanan, kolaborasi, sosial budaya, dan TIK.',
                'isi_materi' => "TKP menilai kecenderungan perilaku dalam bekerja dan melayani masyarakat.\n\nArea yang sering muncul adalah pelayanan publik, profesionalisme, jejaring kerja, sosial budaya, integritas, semangat berprestasi, kemampuan beradaptasi, dan pemanfaatan teknologi informasi.\n\nPada TKP semua pilihan biasanya bernilai. Pilih jawaban yang paling proaktif, etis, solutif, kolaboratif, dan tetap sesuai aturan. Hindari jawaban yang pasif, emosional, atau mengabaikan tanggung jawab.",
            ],
        ];

        foreach ($materials as $code => $material) {
            if (!isset($categories[$code])) {
                continue;
            }

            DB::table('tryout_materis')->insert([
                'tryout_kategori_soal_id' => $categories[$code],
                'judul' => $material['judul'],
                'ringkasan' => $material['ringkasan'],
                'isi_materi' => $material['isi_materi'],
                'status' => 'aktif',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_materis');
    }
};
