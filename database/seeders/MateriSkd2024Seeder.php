<?php

namespace Database\Seeders;

use App\Models\TryoutKategoriSoal;
use App\Models\TryoutMateri;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MateriSkd2024Seeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $names = ['TWK' => 'Tes Wawasan Kebangsaan', 'TIU' => 'Tes Intelegensia Umum', 'TKP' => 'Tes Karakteristik Pribadi'];
            foreach (require __DIR__.'/data/materi-skd-2024.php' as [$kode, $topic, $summary, $intro, $steps, $question, $answer, $explanation, $tip]) {
                $kategori = TryoutKategoriSoal::firstOrCreate(['kode' => $kode], ['nama' => $names[$kode], 'status' => 'aktif']);
                $body = '<h2>Memahami topik</h2><p>'.e($intro).'</p><h2>Cara mempelajari</h2><ol>';
                foreach ($steps as $step) {
                    $body .= '<li>'.e($step).'</li>';
                }
                $body .= '</ol><h2>Contoh latihan</h2><p>'.e($question).'</p><h2>Jawaban dan pembahasan</h2><p><strong>'.e($answer).'</strong></p><p>'.e($explanation).'</p><h2>Hal yang perlu diingat</h2><p>'.e($tip).'</p>';
                $body .= '<h2>Acuan belajar</h2><p>Cakupan topik mengacu pada KepmenPANRB Nomor 321 Tahun 2024. Penjelasan dan contoh di halaman ini adalah bahan belajar mandiri, bukan soal atau kunci resmi. Untuk latihan TKP, penilaian respons bergantung pada konteks dan pilihan jawaban yang tersedia.</p><p><a href="https://www.kemhan.go.id/ropeg/wp-content/uploads/2024/08/Kepmenpanrb-No-321-Th-2024-ttg-NAB-SKD-Pengadaan-PNS-Th-2024.pdf" target="_blank" rel="noopener">Baca dokumen acuan (PDF)</a></p>';
                // Dapat dijalankan ulang tanpa menggandakan atau menimpa hasil edit admin.
                TryoutMateri::firstOrCreate(['tryout_kategori_soal_id' => $kategori->id, 'judul' => $topic], [
                    'topik' => [$topic], 'ringkasan' => $summary, 'isi_materi' => $body, 'status' => 'aktif',
                ]);
            }
        });
    }
}
