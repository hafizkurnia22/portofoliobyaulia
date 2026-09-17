<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutPengaturan extends Model
{
    protected $fillable = [
        'acak_soal',
        'acak_seimbang_kategori',
        'acak_jawaban',
        'durasi_menit',
        'jumlah_soal',
        'jumlah_soal_kategori',
        'durasi_menit_kategori',
        'kisi_kisi_deskripsi',
        'permenpan_file',
        'permenpan_nama',
    ];

    protected $casts = [
        'acak_soal' => 'boolean',
        'acak_seimbang_kategori' => 'boolean',
        'acak_jawaban' => 'boolean',
        'durasi_menit' => 'integer',
        'jumlah_soal' => 'integer',
        'jumlah_soal_kategori' => 'array',
        'durasi_menit_kategori' => 'array',
    ];

    public static function current(): self
    {
        return self::firstOrCreate([], [
            'durasi_menit' => 45,
            'jumlah_soal' => 30,
            'jumlah_soal_kategori' => ['TWK' => 10, 'TIU' => 10, 'TKP' => 10],
            'durasi_menit_kategori' => ['TWK' => 15, 'TIU' => 15, 'TKP' => 15],
            'kisi_kisi_deskripsi' => 'Materi dan simulasi Tryout CPNS disusun berdasarkan kisi-kisi seleksi kompetensi dasar yang berlaku. Admin dapat memperbarui keterangan ini dan mengunggah surat PermenPAN terbaru sebagai acuan belajar peserta.',
        ]);
    }

    public function jumlahSoalKategori(string $kode): int
    {
        return max((int) ($this->jumlah_soal_kategori[strtoupper($kode)] ?? 10), 1);
    }

    public function durasiMenitKategori(string $kode): int
    {
        return max((int) ($this->durasi_menit_kategori[strtoupper($kode)] ?? 15), 1);
    }
}
