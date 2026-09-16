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
            foreach (require __DIR__.'/data/materi-skd-2024.php' as $lesson) {
                [$kode, $topic, $summary] = [$lesson['kategori'], $lesson['topik'], $lesson['ringkasan']];
                $kategori = TryoutKategoriSoal::firstOrCreate(['kode' => $kode], ['nama' => $names[$kode], 'status' => 'aktif']);
                $body = '';

                foreach ($lesson['bagian'] as $sectionTitle => $sectionContent) {
                    $body .= '<h2>'.e($sectionTitle).'</h2>';
                    foreach ((array) $sectionContent as $paragraph) {
                        $body .= '<p>'.e($paragraph).'</p>';
                    }
                }

                $body .= '<h2>Acuan belajar</h2>';
                $body .= '<p>Cakupan topik mengacu pada KepmenPANRB Nomor 321 Tahun 2024 tentang nilai ambang batas SKD CPNS 2024. Penjelasan di halaman ini adalah bahan belajar mandiri yang disusun ulang untuk membantu peserta memahami konsep, bukan salinan soal atau kunci resmi.</p>';
                $body .= '<p>Rujukan pendukung: Pancasila, UUD Negara Republik Indonesia Tahun 1945, Undang-Undang Nomor 24 Tahun 2009 tentang Bendera, Bahasa, dan Lambang Negara, Undang-Undang Nomor 25 Tahun 2009 tentang Pelayanan Publik, serta bahan literasi digital dan pelayanan publik dari sumber pemerintah.</p>';
                $body .= '<p><a href="https://www.kemhan.go.id/ropeg/wp-content/uploads/2024/08/Kepmenpanrb-No-321-Th-2024-ttg-NAB-SKD-Pengadaan-PNS-Th-2024.pdf" target="_blank" rel="noopener">Baca dokumen acuan KepmenPANRB 321/2024 (PDF)</a></p>';

                TryoutMateri::updateOrCreate(
                    ['tryout_kategori_soal_id' => $kategori->id, 'judul' => $topic],
                    ['topik' => [$topic], 'ringkasan' => $summary, 'isi_materi' => $body, 'status' => 'aktif']
                );
            }
        });
    }
}
