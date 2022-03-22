<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Device;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RiskEngine
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // if ($userDevice["REQUEST_METHOD"] == "POST"){
        //     $name = device()
        // }
        // request->... cek isinya apa aja ~ done
        // existing data <- database ~ kurang rssi
        // riskFactor = riskEngine(request, existingData)
        // if riskEngine <= lowRisk return dashboardPage
        // else if riskEngine >= highRisk return loginPage
        // else return otpPage
        $ipAdress = $_SERVER['REMOTE_ADDR'];
        $macAdress = exec("cat /sys/class/net/eth0/address");
        $ipAdressDB = $this->getIpAddress($request);
        $macAdressDB = $this->getMacAddress($request);
        $rssi = "";

        $rssiFromDb = "";
        return $next($request);
    }

    private function riskEngine($ipAdress, $macAdress, $ipAdressDB, $macAdressDB) {
        
    }

    public function getIpAddress($request){
        return DB::select('SELECT ip_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }

    public function getMacAddress($request) {
        return DB::select('SELECT mac_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.users_id WHERE users.email=?;', [$request->email]);
    }
}
