<?php

namespace App\Http\Controllers;

use App\Models\Device as ModelsDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Device extends Controller
{

    public function getUser($id) {
        $user = User::where('id', $id);
        return $user;
    }

    // public function afterRegist() {
    //     $device = ModelsDevice::all();
    //     $user_id = $device->user_id;
    //     $username = User::where('id', $user_id);
    //     $username = $username->username;
    //     return view('registDashboard', [
    //         'username' => $username,
    //         'status' => 'Pending',
    //         'deskripsi' => $device->device,
    //         'merk' => $device->merk,
    //         'mac' => $device->mac_address,
    //         'umur_registrasi' => $device->umur_registrasi,
    //         'tgl_registrasi' => $device->tgl_register
    //     ]);
    // }

    public function store(Request $request) {
        $user = $request->username;
        $device = $request->device;
        $vendor = $request->vendor;
        $mac = exec("/sbin/ip addr|/bin/grep link/ether | /bin/awk '{print $2}'");
        ModelsDevice::create([
            'user_id' => Auth::user()->id,
            'merk' => $vendor,
            'mac_address' => $mac,
            'ip_Address' => $_SERVER['REMOTE_ADDR'],
            'Deskripsi' => "Menunggu Persetujuan",
            'umur_registrasi' => "0",
            'deskripsi' => $device,
            'tgl_register' => date('Y-m-d H:i:s')
        ]);
        return redirect("/dashboard/pending");
    }
}
