<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Device;

class Admin extends Controller
{
    public function index() {
        $device = $this->getUserDevice();
        return view('admin', [
            'devices' => $device
        ]);
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
        return DB::select('SELECT * FROM users INNER JOIN device_from_users ON users.id=device_from_users.user_id; ');
    }
}
