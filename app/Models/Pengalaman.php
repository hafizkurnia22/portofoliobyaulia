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
}