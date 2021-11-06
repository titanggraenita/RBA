<?php

namespace App\Http\Controllers;

use App\Models\Device as ModelsDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Device extends Controller
{

    public function index() {
        $MAC = exec("/sbin/ip addr|/bin/grep link/ether | /bin/awk '{print $2}'");
        $userDevice = $this->getUserDevice();
        return view('dashboard', [
            "MAC" => $MAC,
            "user_devices" => $userDevice
        ]);
    }

    public function getUserDevice() {
        return DB::table('device_from_users')->where('user_id', Auth::id())->get();
    }


    public function store(Request $request) {
        $device = $request->device;
        $vendor = $request->vendor;
        $mac = exec("/sbin/ip addr|/bin/grep link/ether | /bin/awk '{print $2}'");
        ModelsDevice::create([
            'user_id' => Auth::id(),
            'merk' => $vendor,
            'mac_address' => $mac,
            'ip_Address' => $_SERVER['REMOTE_ADDR'],
            'status' => "Menunggu Persetujuan",
            'umur_registrasi' => "0",
            'deskripsi' => $device,
            'tgl_register' => date('Y-m-d H:i:s')
        ]);
        return redirect("/dashboard");
    }
}
