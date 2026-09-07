<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutKategoriSoal extends Model
{
    protected $fillable = [
        'kode',
        'nama',
        'deskripsi',
        'status',
    ];

    public function soals()
    {
        return $this->hasMany(TryoutSoal::class);
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
