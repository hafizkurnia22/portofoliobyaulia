<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TryoutPeserta extends Model
{
    protected $fillable = [
        'nama',
        'username',
        'pin_hash',
        'status',
        'last_login_at',
    ];

    protected $hidden = [
        'pin_hash',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function riwayats()
    {
        return $this->hasMany(TryoutRiwayat::class);
    }
}
