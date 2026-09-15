<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutMateri extends Model
{
    protected $fillable = [
        'tryout_kategori_soal_id',
        'judul',
        'ringkasan',
        'isi_materi',
        'status',
        'topik',
    ];

    protected $casts = ['topik' => 'array'];

    // Cakupan SKD pada KepmenPANRB Nomor 321 Tahun 2024.
    public const TOPIK = [
        'TWK' => ['Nasionalisme', 'Integritas', 'Bela Negara', 'Pilar Negara', 'Bahasa Negara'],
        'TIU' => ['Kemampuan Verbal', 'Kemampuan Numerik', 'Kemampuan Figural'],
        'TKP' => ['Pelayanan Publik', 'Jejaring Kerja', 'Sosial Budaya', 'Teknologi Informasi dan Komunikasi', 'Profesionalisme', 'Anti Radikalisme'],
    ];

    public function topikPelajaran(): array
    {
        if ($this->topik !== null) {
            return $this->topik;
        }

        // Materi lama hanya dikelompokkan jika topiknya disebut di judul.
        $judul = mb_strtolower($this->judul);
        $aliases = ['Pilar Negara' => 'pilar kebangsaan', 'Kemampuan Verbal' => 'verbal', 'Kemampuan Numerik' => 'numerik'];
        return array_values(array_filter(self::TOPIK[$this->kategoriSoal->kode ?? ''] ?? [],
            fn ($topik) => str_contains($judul, mb_strtolower($topik)) ||
                (isset($aliases[$topik]) && str_contains($judul, $aliases[$topik]))));
    }

    public function kategoriSoal()
    {
        return $this->belongsTo(TryoutKategoriSoal::class, 'tryout_kategori_soal_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
