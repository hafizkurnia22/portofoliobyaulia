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
        ]);
    }
}
