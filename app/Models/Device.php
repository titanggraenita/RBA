<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = 'device_from_users';
    public $timestamps = false;

    protected $fillable = [
        'user_id', 'merk', 'deskripsi', 'mac_address', 'ip_Address', 'tgl_register', 'umur_registrasi', 'status'
    ];
}
