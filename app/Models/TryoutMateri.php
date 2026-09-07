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
    ];

    public function kategoriSoal()
    {
        return $this->belongsTo(TryoutKategoriSoal::class, 'tryout_kategori_soal_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
