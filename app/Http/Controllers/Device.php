<?php

namespace App\Http\Controllers;

use App\Models\Device as ModelsDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class Device extends Controller
{

    public function index() {
        $MAC = $this->getMacAddress();
        $userDevice = $this->getUserDevice();
        //$loginTime = $this->getLoginTime();
        $deviceCount = count($userDevice);
        return view('dashboard', [
            'deviceCount' => $deviceCount,
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
        $osType = $request->deviceOS;
        $mac = $this->getMacAddress();
        ModelsDevice::create([
            'user_id' => Auth::id(),
            'merk' => $vendor,
            'mac_address' => $mac,
            'ip_Address' => $_SERVER['REMOTE_ADDR'],
            'status' => "Menunggu Persetujuan",
            'umur_registrasi' => "0",
            'os_type' => $osType,
            'deskripsi' => $device,
            'tgl_register' => date('Y-m-d H:i:s')
        ]);
        return redirect("/dashboard");
    }

    function getMacAddress(): string {
        return exec("cat /sys/class/net/wlp3s0/address");
    }

    public function guzzle(){
        $client = new Client();
        $res = $client->request('GET', 'http://10.252.209.202/rssi_service.php');
        echo $res->getStatusCode();
        echo $res->getHeader('content-type')[0];
        echo $res->getBody();
    }
}
