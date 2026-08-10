<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sertifikasi extends Model
{
    protected $table = 'sertifikasis';

    protected $fillable = [
        'nama_sertifikat',
        'penyelenggara',
        'tahun',
        'deskripsi',
        'file_pdf',
    ];

    public function scopeByLatestYear($query)
    {
        return $query
            ->orderByRaw("
                CAST(COALESCE(NULLIF(RIGHT(REGEXP_REPLACE(tahun, '[^0-9]', ''), 4), ''), '0') AS UNSIGNED) DESC
            ")
            ->latest();
    }
}
