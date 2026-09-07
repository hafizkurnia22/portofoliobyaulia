<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutRiwayat extends Model
{
    protected $fillable = [
        'tryout_peserta_id',
        'total_soal',
        'total_dijawab',
        'total_benar',
        'total_ragu',
        'total_skor',
        'durasi_detik',
        'detail_jawaban',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'detail_jawaban' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(TryoutPeserta::class, 'tryout_peserta_id');
    }
}
