<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Device;

class Admin extends Controller
{
    public function index() {
        $device = $this->getUserDevice();
        if(Auth::user()->id_role == '1'){
            return view('admin', [
                'devices' => $device
            ]);
        } else {
            return redirect('/dashboard');
        }
    }

    public function approve(Request $request) {
        $id = $request->id;
        DB::update("UPDATE device_from_users SET status = ? WHERE id = ?", ['Disetujui', $id]);
        return redirect("/admin");
    }

    public function delete(Request $request) {
        $id = $request->id;
        $device = Device::find($id);
        $device->delete();
        return redirect("/admin");
    }
    
    private function getUserDevice() {
        return Device::all();
    }
}
