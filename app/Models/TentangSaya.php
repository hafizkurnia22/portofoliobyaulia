<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TentangSaya extends Model
{
    protected $fillable = [
    'nama',
    'bidang',
    'status',
    'deskripsi_1',
    'deskripsi_2',
    'foto',
    'whatsapp',
    'email_kontak',
    'facebook',
    'instagram',
    'tiktok',
];
}