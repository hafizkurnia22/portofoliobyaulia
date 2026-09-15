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
    ];

    public static function current(): self
    {
        return self::firstOrCreate([], [
            'durasi_menit' => 45,
            'jumlah_soal' => 30,
            'kisi_kisi_deskripsi' => 'Materi dan simulasi Tryout CPNS disusun berdasarkan kisi-kisi seleksi kompetensi dasar yang berlaku. Admin dapat memperbarui keterangan ini dan mengunggah surat PermenPAN terbaru sebagai acuan belajar peserta.',
        ]);
    }
}
