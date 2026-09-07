<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutPengaturan extends Model
{
    protected $fillable = [
        'acak_soal',
        'acak_jawaban',
    ];

    protected $casts = [
        'acak_soal' => 'boolean',
        'acak_jawaban' => 'boolean',
    ];

    public static function current(): self
    {
        return self::firstOrCreate([]);
    }
}
