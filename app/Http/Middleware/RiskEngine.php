<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Device;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $macAddress = exec("cat /sys/class/net/eth0/address");
        $ipAddressDB = $this->getIpAddress($request);
        $macAddressDB = $this->getMacAddress($request);
        $rssi = "";
        $rssiFromDb = array("");
//        $datetimeTolerance = $request();
        Log::alert("Email : ".$request->email);
        Log::alert(date("Y-m-d H:i:s"));
        //Log::alert($request->header());
        //Log::alert(("LoginTime : ".$request->loginTime));
        dd($_SERVER['HTTP_USER_AGENT']);
        if (count($macAddressDB) < 1 || count($ipAddressDB) < 1 || count($rssiFromDb) < 1) {
            Log::alert("No Device yet");
            return $next($request);
        }
        $riskEngine = $this->riskEngine($ipAddress, $macAddress, $rssi, $ipAddressDB, $macAddressDB, $rssiFromDb);
        Log::alert("Risk Engine : ".$riskEngine);
        return $next($request);
    }

//    public function loginTimeTolerance()
//    {
//        $hour = (int)date('H');
//        if($hour <= 8 || $hour >= 17){
//            $datetimeTolerance = $this->loginTimeTolerance($request);
//            return $next($request);
//        } else {
//            return redirect("/dashboard");
//        }
//    }

    /*
    2. Buatlah sebuah fungsi untuk menghitung toleransi waktu login
    */

    private function riskEngine($ipAddress, $macAddress, $rssi, $ipAddressDB, $macAddressDB, $rssiFromDb): int {
        $riskValue = 0;
        foreach ($ipAddressDB as $ipAddressEl) {
            Log::alert("IP Checking -> ". $ipAddressEl->ip_address);
            if ($ipAddressEl->ip_address == $ipAddress) {
                Log::alert("Same IP !");
                $riskValue += 25;break;
            }
        }
        foreach ($macAddressDB as $macAddressEl) {
            Log::alert("MAC Checking -> ". $macAddressEl->mac_address);
            if ($macAddressEl->mac_address == $macAddress) {
                Log::alert("Same MAC !");
                $riskValue +=25;break;
            }
        }
        foreach ($rssiFromDb as $rssiEl) {
            Log::alert("RSSI Checking -> ". $rssiEl);
            if ($rssiEl == $rssi) {
                Log::alert("Same RSSI !");
                $riskValue += 25;break;
            }
        }
        return $riskValue;
    }



    public function getIpAddress($request): array
    {
        return DB::select('SELECT ip_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }

    public function getMacAddress($request): array
    {
        return DB::select('SELECT mac_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }
}