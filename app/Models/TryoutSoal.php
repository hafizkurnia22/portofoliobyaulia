<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutSoal extends Model
{
    protected $fillable = [
        'kode_soal',
        'tryout_kategori_soal_id',
        'kategori',
        'pertanyaan',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'opsi_e',
        'jawaban_benar',
        'skor_a',
        'skor_b',
        'skor_c',
        'skor_d',
        'skor_e',
        'pembahasan',
        'status',
    ];

    protected $casts = [
        'skor_a' => 'integer',
        'skor_b' => 'integer',
        'skor_c' => 'integer',
        'skor_d' => 'integer',
        'skor_e' => 'integer',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function kategoriSoal()
    {
        return $this->belongsTo(TryoutKategoriSoal::class, 'tryout_kategori_soal_id');
    }
}
