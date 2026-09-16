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
                $lesson['bagian'] = $this->completeSections($lesson);
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

    private function completeSections(array $lesson): array
    {
        $topic = $lesson['topik'];
        $category = $lesson['kategori'];
        $details = $this->topicDetails()[$topic] ?? [];
        $sections = $lesson['bagian'];
        $completed = [];

        foreach ($sections as $title => $content) {
            $completed[$title] = $content;
            if ($title === 'Konsep kunci' && isset($details['materi'])) {
                $completed['Materi pokok yang perlu dikuasai'] = $details['materi'];
            }
            if (($title === 'Pola soal yang sering muncul' || $title === 'Strategi menjawab') && isset($details['soal'])) {
                $completed['Bentuk soal yang sering keluar'] = $details['soal'];
            }
        }

        $completed['Bacaan dan penguasaan tambahan'] = $details['bacaan'] ?? $this->categoryReading()[$category];

        return $completed;
    }

    private function categoryReading(): array
    {
        return [
            'TWK' => [
                'Bacaan dasar TWK meliputi Pembukaan UUD 1945, nilai Pancasila, bentuk Negara Kesatuan Republik Indonesia, semboyan Bhinneka Tunggal Ika, hak dan kewajiban warga negara, serta penggunaan bahasa dan simbol negara.',
                'Saat belajar TWK, jangan hanya menghafal istilah. Hubungkan setiap konsep dengan kasus sehari-hari: pelayanan yang adil, musyawarah, penghormatan hak warga, penolakan diskriminasi, dan kepentingan nasional.',
            ],
            'TIU' => [
                'Bacaan dasar TIU adalah latihan logika, aritmetika, pemahaman pola, dan penalaran visual. Materi ini tidak bergantung pada hafalan aturan pemerintahan, tetapi pada kebiasaan membaca soal dengan teliti dan memilih strategi paling singkat.',
                'Untuk menguatkan TIU, buat catatan rumus sederhana, daftar pola deret, tipe hubungan kata, dan kesalahan hitung yang sering terjadi. Latihan singkat harian lebih efektif daripada membaca teori panjang tanpa praktik.',
            ],
            'TKP' => [
                'Bacaan dasar TKP berkaitan dengan nilai ASN BerAKHLAK, pelayanan publik, kolaborasi, adaptasi sosial, keamanan informasi, profesionalisme, dan sikap menolak kekerasan atau intoleransi.',
                'Dalam TKP, jawaban dinilai berdasarkan kualitas respons. Pilihan terbaik biasanya aktif, etis, solutif, aman, dan sesuai kewenangan; bukan pilihan yang pasif, emosional, atau hanya menguntungkan diri sendiri.',
            ],
        ];
    }

    private function topicDetails(): array
    {
        return [
            'Nasionalisme' => [
                'materi' => [
                    'Pelajari identitas nasional, kepentingan nasional, persatuan, penghormatan terhadap budaya sendiri dan budaya lain, serta cara menjaga nama baik bangsa dalam kerja sama internasional.',
                    'Pahami perbedaan nasionalisme positif dan chauvinisme. Nasionalisme positif membangun kontribusi, sedangkan chauvinisme merendahkan pihak lain dan berisiko memecah hubungan.',
                ],
                'soal' => [
                    'Soal biasanya meminta sikap saat ada konflik kepentingan kelompok, fanatisme sempit, kegiatan budaya, penggunaan produk dalam negeri, atau tindakan menjaga persatuan di lingkungan kerja.',
                    'Pilih jawaban yang mengutamakan kepentingan Indonesia, menjaga identitas nasional, menghormati perbedaan, dan memberi solusi yang dapat dijalankan.',
                ],
                'bacaan' => 'Baca kembali Pembukaan UUD 1945, makna persatuan Indonesia, sejarah sumpah pemuda, simbol negara, dan contoh sikap bangga menggunakan bahasa serta budaya Indonesia secara bermartabat.',
            ],
            'Integritas' => [
                'materi' => [
                    'Kuasai makna jujur, konsisten, bertanggung jawab, anti manipulasi, anti gratifikasi, objektif, dan berani melaporkan pelanggaran melalui jalur yang benar.',
                    'Integritas juga berkaitan dengan penggunaan data yang benar, tidak memalsukan dokumen, tidak menyalahgunakan jabatan, dan tidak memberikan perlakuan khusus karena kedekatan pribadi.',
                ],
                'soal' => [
                    'Soal sering muncul dalam bentuk titipan, tekanan atasan, permintaan teman, hadiah, pemalsuan absensi, kebocoran data, atau konflik kepentingan.',
                    'Jawaban terbaik menolak pelanggaran, tetap sopan, menjaga bukti, dan menyelesaikan melalui prosedur tanpa memperkeruh keadaan.',
                ],
                'bacaan' => 'Perkuat bacaan tentang akuntabilitas, konflik kepentingan, budaya antikorupsi, dan etika pelayanan publik. Buat contoh kecil dari kehidupan kerja agar konsep integritas tidak terasa abstrak.',
            ],
            'Bela Negara' => [
                'materi' => [
                    'Pelajari cinta tanah air, kesadaran berbangsa dan bernegara, keyakinan pada Pancasila, rela berkorban, serta kemampuan awal membela negara sesuai peran warga sipil.',
                    'Bela negara di kehidupan sehari-hari mencakup menjaga ketertiban, menolak hoaks, membantu saat bencana, menjaga fasilitas umum, dan melaporkan ancaman melalui jalur resmi.',
                ],
                'soal' => [
                    'Soal sering berupa rumor ancaman, ajakan kekerasan, bencana, konflik warga, ancaman digital, atau keadaan yang membutuhkan koordinasi cepat.',
                    'Pilih tindakan yang berani tetapi tetap aman, berbasis fakta, tidak main hakim sendiri, dan sesuai kewenangan.',
                ],
                'bacaan' => 'Baca konsep bela negara dalam pendidikan kewarganegaraan, contoh partisipasi warga saat darurat, dan prinsip verifikasi informasi sebelum menyebarkan kabar.',
            ],
            'Pilar Negara' => [
                'materi' => [
                    'Materi pokoknya adalah Pancasila, UUD 1945, NKRI, dan Bhinneka Tunggal Ika. Pahami fungsi masing-masing, bukan hanya hafal istilah.',
                    'Pancasila menjadi dasar nilai; UUD 1945 menjadi hukum dasar; NKRI menegaskan bentuk negara; Bhinneka Tunggal Ika menjaga persatuan dalam keberagaman.',
                ],
                'soal' => [
                    'Soal dapat meminta penerapan sila, hak dan kewajiban warga negara, musyawarah, toleransi, keadilan sosial, atau keputusan yang sesuai konstitusi.',
                    'Pilih jawaban yang seimbang: taat aturan, menghormati hak, menyelesaikan masalah, dan tidak menindas minoritas.',
                ],
                'bacaan' => 'Baca Pembukaan UUD 1945, pasal dasar tentang hak warga negara, nilai tiap sila Pancasila, makna NKRI, dan contoh penerapan Bhinneka Tunggal Ika di masyarakat.',
            ],
            'Bahasa Negara' => [
                'materi' => [
                    'Kuasai kalimat efektif, kata baku, ejaan, gagasan utama, simpulan, koherensi paragraf, makna kata sesuai konteks, dan fungsi bahasa Indonesia sebagai bahasa persatuan.',
                    'UU 24/2009 dapat menjadi bacaan dasar untuk memahami kedudukan bahasa Indonesia, bendera, lambang negara, dan lagu kebangsaan sebagai simbol negara.',
                ],
                'soal' => [
                    'Soal yang sering muncul: perbaikan kalimat, pemilihan kata, gagasan utama, simpulan paragraf, urutan kalimat, makna istilah, dan kesalahan pemborosan kata.',
                    'Pilih jawaban yang paling jelas, hemat, logis, dan sesuai konteks bacaan.',
                ],
                'bacaan' => 'Baca ringkasan PUEBI/EYD terbaru, contoh kalimat efektif, dan teks pendek bertema kebangsaan. Latih mencari ide pokok dalam satu paragraf sebelum membaca pilihan jawaban.',
            ],
            'Analogi Verbal' => [
                'materi' => [
                    'Kuasai hubungan kata: sinonim, antonim, alat-fungsi, sebab-akibat, bagian-keseluruhan, tempat-aktivitas, profesi-hasil, kategori-anggota, dan urutan proses.',
                    'Kunci analogi adalah relasi, bukan kedekatan tema. Dua kata bisa sama-sama berkaitan tetapi memiliki pola yang berbeda.',
                ],
                'soal' => [
                    'Soal keluar dalam bentuk pasangan kata A : B = C : D. Pengecoh biasanya satu tema tetapi beda hubungan atau urutannya terbalik.',
                    'Buat kalimat relasi singkat dan terapkan pada semua pilihan.',
                ],
                'bacaan' => 'Perbanyak kosakata bahasa Indonesia, istilah profesi, fungsi benda, dan hubungan sebab akibat sederhana. Latihan 10 pasangan kata per hari sudah sangat membantu.',
            ],
            'Silogisme' => [
                'materi' => [
                    'Pelajari logika himpunan: semua, sebagian, beberapa, tidak ada, jika-maka, hanya jika, dan negasi sederhana.',
                    'Pahami bahwa kesimpulan harus pasti benar berdasarkan premis, bukan berdasarkan pengetahuan umum.',
                ],
                'soal' => [
                    'Soal muncul sebagai dua atau tiga premis lalu meminta kesimpulan. Pengecoh memakai kesimpulan yang mungkin benar, tetapi tidak pasti.',
                    'Gunakan diagram lingkaran atau simbol A -> B untuk menghindari kesalahan membalik hubungan.',
                ],
                'bacaan' => 'Latih soal himpunan sederhana dan logika pernyataan. Catat jebakan umum: semua B adalah A, padahal premisnya hanya semua A adalah B.',
            ],
            'Analitis' => [
                'materi' => [
                    'Kuasai penyusunan urutan, posisi duduk, jadwal, relasi keluarga, pemetaan tugas, syarat prioritas, dan aturan yang saling membatasi.',
                    'Perhatikan kata tepat sebelum, sebelum, setelah, tidak berdampingan, minimal, maksimal, hanya jika, dan kecuali.',
                ],
                'soal' => [
                    'Soal biasanya memberi beberapa aturan lalu menanyakan susunan yang mungkin, pasti benar, atau tidak mungkin.',
                    'Buat tabel kecil, isi aturan paling ketat lebih dulu, lalu cek ulang setiap syarat.',
                ],
                'bacaan' => 'Latih puzzle logika ringan seperti urutan presentasi, jadwal kelas, dan posisi meja. Fokus pada ketelitian membaca syarat.',
            ],
            'Berhitung' => [
                'materi' => [
                    'Kuasai operasi campuran, pecahan, desimal, persentase, pangkat sederhana, akar sederhana, rata-rata, perbandingan, skala, dan konversi satuan.',
                    'Materi ini menguji kecepatan dan ketelitian. Banyak soal dapat diselesaikan dengan menyederhanakan angka sebelum menghitung panjang.',
                ],
                'soal' => [
                    'Soal yang sering muncul: persentase, diskon, rasio, rata-rata, operasi campuran, bilangan bulat, pecahan, dan hitungan praktis sehari-hari.',
                    'Cek urutan operasi dan satuan sebelum memilih jawaban.',
                ],
                'bacaan' => 'Buat lembar kecil berisi pecahan umum dan persentasenya: 1/2, 1/3, 1/4, 1/5, 1/8, 3/4. Ini mempercepat banyak soal.',
            ],
            'Deret Angka' => [
                'materi' => [
                    'Kuasai pola tambah, kurang, kali, bagi, selisih bertingkat, kuadrat, pangkat, bilangan prima, Fibonacci sederhana, dan pola berselang ganjil-genap.',
                    'Jangan terpaku pada satu pola jika hanya cocok di awal. Pola yang benar harus menjelaskan semua angka yang tersedia.',
                ],
                'soal' => [
                    'Soal meminta angka berikutnya atau angka yang hilang. Pola bisa tunggal, bertingkat, atau berselang dua kelompok.',
                    'Hitung selisih lebih dulu; jika belum cocok, cek selisih kedua, perkalian, atau pola posisi ganjil-genap.',
                ],
                'bacaan' => 'Latih 5 deret angka setiap hari dan tulis alasan polanya. Jangan hanya menghafal jawaban.',
            ],
            'Perbandingan Kuantitatif' => [
                'materi' => [
                    'Kuasai perbandingan P dan Q, sifat bilangan positif/negatif, nol, pecahan, kuadrat, akar, persamaan sederhana, dan informasi cukup atau tidak cukup.',
                    'Topik ini menuntut kehati-hatian terhadap syarat. Satu variabel dapat menghasilkan hubungan berbeda pada rentang berbeda.',
                ],
                'soal' => [
                    'Soal memberi dua kuantitas dan meminta hubungan: lebih besar, lebih kecil, sama, atau tidak dapat ditentukan.',
                    'Uji nilai kecil, besar, nol, negatif, dan pecahan selama memenuhi syarat.',
                ],
                'bacaan' => 'Pelajari sifat dasar aljabar dan bilangan. Biasakan bertanya: apakah hubungan ini selalu benar untuk semua nilai yang memenuhi syarat?',
            ],
            'Soal Cerita' => [
                'materi' => [
                    'Kuasai jarak-waktu-kecepatan, pekerjaan bersama, umur, keuntungan-rugi, perbandingan, campuran, persentase, bunga sederhana, dan konversi satuan.',
                    'Tantangan utama adalah menerjemahkan kalimat menjadi model matematika.',
                ],
                'soal' => [
                    'Soal biasanya memuat konteks sehari-hari dengan beberapa angka. Tidak semua angka selalu dipakai.',
                    'Tulis diketahui, ditanya, hubungan, lalu hitung. Samakan satuan terlebih dahulu.',
                ],
                'bacaan' => 'Latih membaca soal pelan pada kalimat pertama dan terakhir. Kalimat terakhir biasanya menentukan apa yang benar-benar ditanyakan.',
            ],
            'Analogi Figural' => [
                'materi' => [
                    'Kuasai rotasi, pencerminan, perpindahan titik, perubahan jumlah sisi, perubahan arsiran, penambahan/pengurangan unsur, dan perubahan ukuran relatif.',
                    'Perubahan pada gambar pertama harus diterapkan pada gambar berikutnya dengan aturan yang sama.',
                ],
                'soal' => [
                    'Soal muncul sebagai hubungan gambar A ke B, lalu peserta memilih transformasi yang sama untuk gambar C.',
                    'Amati satu unsur demi satu unsur: arah, posisi, jumlah, warna, dan bentuk.',
                ],
                'bacaan' => 'Latihan figural lebih efektif dengan gambar. Biasakan menyebut aturan perubahan dengan kata-kata sebelum melihat pilihan.',
            ],
            'Ketidaksamaan' => [
                'materi' => [
                    'Kuasai ciri pembeda gambar: jumlah sisi, sudut, simetri, arah, titik, garis, arsiran, posisi elemen, dan keteraturan pola.',
                    'Jawaban yang berbeda harus bisa dijelaskan dengan aturan kelompok, bukan sekadar terlihat aneh.',
                ],
                'soal' => [
                    'Soal meminta satu gambar yang tidak sama dengan kelompok lain. Pengecoh sering dibuat paling mencolok, tetapi bukan yang benar.',
                    'Cari ciri yang dimiliki mayoritas, lalu pilih gambar yang melanggar ciri itu.',
                ],
                'bacaan' => 'Latih mengelompokkan bentuk dasar dan pola visual. Gunakan alasan singkat: berbeda karena jumlah sudut, arah bukaan, atau posisi titik.',
            ],
            'Serial' => [
                'materi' => [
                    'Kuasai urutan perubahan gambar: rotasi berkala, titik berpindah, jumlah unsur bertambah, arsiran bergeser, bentuk berganti, dan pola dua langkah.',
                    'Jika ada beberapa unsur, setiap unsur dapat bergerak dengan aturan sendiri.',
                ],
                'soal' => [
                    'Soal meminta gambar selanjutnya dari rangkaian. Pilihan salah sering mengikuti satu pola tetapi mengabaikan pola unsur lain.',
                    'Pisahkan pola arah, posisi, jumlah, dan arsiran sebelum memilih.',
                ],
                'bacaan' => 'Latihan serial figural perlu visual. Mulai dari pola panah dan titik sederhana, lalu tingkatkan ke gambar dengan beberapa unsur.',
            ],
            'Pelayanan Publik' => [
                'materi' => [
                    'Kuasai prinsip layanan: ramah, jelas, cepat, adil, responsif, mudah diakses, tidak diskriminatif, dan sesuai prosedur.',
                    'Pelayanan publik juga menuntut empati kepada pengguna layanan tanpa membocorkan data atau memberi keistimewaan yang tidak sah.',
                ],
                'soal' => [
                    'Soal sering berisi warga marah, berkas kurang, antrean panjang, pengguna gaptek, permintaan di luar aturan, atau keluhan layanan.',
                    'Jawaban terbaik membantu aktif, menjelaskan langkah, memberi alternatif sah, dan menjaga keadilan antrean.',
                ],
                'bacaan' => 'Baca nilai dasar pelayanan publik, standar pelayanan, hak pengguna layanan, dan contoh komunikasi sederhana kepada masyarakat awam.',
            ],
            'Jejaring Kerja' => [
                'materi' => [
                    'Kuasai kolaborasi, koordinasi lintas unit, pembagian peran, pertukaran informasi, klarifikasi data, dan tindak lanjut hasil rapat.',
                    'Jejaring kerja yang baik dibangun untuk tujuan organisasi, bukan untuk memanfaatkan kedekatan pribadi.',
                ],
                'soal' => [
                    'Soal sering memuat konflik data, unit sulit diajak kerja sama, target bersama, atau kebutuhan koordinasi mendadak.',
                    'Pilih jawaban yang mengajak pihak terkait, menyamakan pemahaman, mencatat keputusan, dan memonitor tindak lanjut.',
                ],
                'bacaan' => 'Pelajari komunikasi kerja, kolaborasi lintas fungsi, notulen singkat, dan etika berbagi informasi.',
            ],
            'Sosial Budaya' => [
                'materi' => [
                    'Kuasai adaptasi di masyarakat majemuk, toleransi, komunikasi lintas budaya, anti stereotip, penghargaan terhadap kebiasaan berbeda, dan penyelesaian konflik secara sopan.',
                    'Perbedaan latar belakang tidak boleh menjadi alasan diskriminasi. Fokus pada fakta dan kebutuhan kerja.',
                ],
                'soal' => [
                    'Soal sering memuat perbedaan agama, budaya, bahasa, kebiasaan kerja, jadwal ibadah, atau salah paham antaranggota tim.',
                    'Jawaban terbaik membuka dialog, mencari penyesuaian wajar, dan tetap menjaga target kerja.',
                ],
                'bacaan' => 'Perkuat wawasan Bhinneka Tunggal Ika, toleransi, komunikasi inklusif, dan contoh pelayanan kepada masyarakat beragam.',
            ],
            'Teknologi Informasi dan Komunikasi' => [
                'materi' => [
                    'Kuasai keamanan akun, kata sandi, verifikasi dua langkah, phishing, perlindungan data pribadi, penggunaan aplikasi resmi, etika komunikasi digital, dan verifikasi informasi.',
                    'Teknologi dipakai untuk meningkatkan kinerja, tetapi keamanan dan kerahasiaan data tetap harus dijaga.',
                ],
                'soal' => [
                    'Soal sering berisi pesan mencurigakan, permintaan kata sandi, tautan palsu, gangguan aplikasi, penyebaran data, atau penggunaan grup kerja.',
                    'Jawaban terbaik memverifikasi melalui kanal resmi, melaporkan masalah, dan tidak membagikan data sensitif.',
                ],
                'bacaan' => 'Baca literasi digital dasar, keamanan informasi, etika bermedia digital, dan perlindungan data pribadi.',
            ],
            'Profesionalisme' => [
                'materi' => [
                    'Kuasai tanggung jawab, mutu kerja, disiplin, prioritas, komunikasi kendala, kemampuan belajar, evaluasi diri, dan keberanian memperbaiki kesalahan.',
                    'Profesionalisme berarti hasil kerja dapat dipertanggungjawabkan, bukan hanya selesai cepat.',
                ],
                'soal' => [
                    'Soal sering memuat deadline, kesalahan laporan, perubahan tugas, kritik, beban kerja, rekan pasif, atau keterbatasan kemampuan.',
                    'Pilih jawaban yang memperbaiki kualitas, berkomunikasi lebih awal, meminta bantuan relevan, dan mencegah pengulangan masalah.',
                ],
                'bacaan' => 'Pelajari manajemen waktu, etika kerja, standar mutu, dan cara menerima umpan balik secara dewasa.',
            ],
            'Anti Radikalisme' => [
                'materi' => [
                    'Kuasai sikap menolak kekerasan, intoleransi, ujaran kebencian, pemaksaan ideologi, dan ajakan melawan hukum. Nilai tindakan berdasarkan bukti, bukan identitas seseorang.',
                    'Respons yang baik menjaga keselamatan, tidak memperluas provokasi, dan menggunakan saluran pelaporan yang tepat.',
                ],
                'soal' => [
                    'Soal sering memuat ajakan kekerasan, konten provokatif, tekanan kelompok, pernyataan intoleran, atau ancaman konkret.',
                    'Jawaban terbaik menolak ajakan, tidak menyebarkan konten, menyimpan informasi relevan secara aman, dan melapor jika ada ancaman nyata.',
                ],
                'bacaan' => 'Pelajari wawasan kebangsaan, toleransi, literasi digital, pencegahan ekstremisme kekerasan, dan cara menghadapi provokasi secara aman.',
            ],
        ];
    }
}
