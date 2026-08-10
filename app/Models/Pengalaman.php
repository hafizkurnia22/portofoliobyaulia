<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengalaman extends Model
{
    protected $table = 'pengalamans';

    protected $fillable = [
        'nama_perusahaan',
        'jabatan',
        'deskripsi',
        'periode',
        'logo'
    ];

    public function scopeByLatestYear($query)
    {
        return $query
            ->orderByRaw("
                CASE
                    WHEN LOWER(periode) LIKE '%sekarang%'
                        OR LOWER(periode) LIKE '%present%'
                        OR LOWER(periode) LIKE '%saat ini%'
                    THEN 9999
                    ELSE CAST(COALESCE(NULLIF(RIGHT(REGEXP_REPLACE(periode, '[^0-9]', ''), 4), ''), '0') AS UNSIGNED)
                END DESC
            ")
            ->latest();
    }
}
