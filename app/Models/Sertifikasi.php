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
}