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
        'user_id', 'merk', 'deskripsi', 'mac_address', "access_point",
        'ip_Address', 'tgl_register', 'umur_registrasi', 'status', 'os_type'
    ];

    public function user() {
        return $this->hasMany(User::class);
    }
}
