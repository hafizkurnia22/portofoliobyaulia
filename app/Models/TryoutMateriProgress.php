<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutMateriProgress extends Model
{
    protected $table = 'tryout_materi_progresses';
    protected $fillable = [
        'tryout_peserta_id',
        'tryout_materi_id',
        'is_bookmarked',
        'read_at',
    ];

    protected $casts = [
        'is_bookmarked' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function peserta()
    {
        return $this->belongsTo(TryoutPeserta::class, 'tryout_peserta_id');
    }

    public function materi()
    {
        return $this->belongsTo(TryoutMateri::class, 'tryout_materi_id');
    }
}
