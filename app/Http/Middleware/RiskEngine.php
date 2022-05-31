<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Device;
use App\Http\Controllers\OtpController;
use Closure;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\MockObject\Rule\Parameters;

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
        $email = $request->request->get('email');
        $ipAddress = $_SERVER['REMOTE_ADDR'];
        $macAddress = exec("cat /sys/class/net/eth0/address");
        $osType = $this->detectClientOS();
        $ipAddressDB = $this->getIpAddress($request);
        $macAddressDB = $this->getMacAddress($request);
        $osTypeDB = $this->getOsType($request);
        $rssi = "";
        $rssiFromDb = array("");
        if (count($macAddressDB) < 1 || count($ipAddressDB) < 1 || count($rssiFromDb) < 1) {
            Log::alert("No Device yet");
            return $next($request);
        }
        $riskEngine = $this->riskEngine(
            $ipAddress, $macAddress, $rssi, $osType, $ipAddressDB, $macAddressDB, $rssiFromDb, $osTypeDB
        );
        Log::alert("Risk engine : " . $riskEngine);
        if ($riskEngine < 25) {
            return redirect()->route("login");
        } else if ($riskEngine < 68) {
           //return redirect()->route("login");
           return redirect("dashboard/otp")->withInput();
        } else {
            return $next($request);
        }
    }
    //bikin halaman OTP

    public function isLoginAbnormal():bool
    {
        $hour = (int)date('H');
        Log::alert("Hour : " . $hour);
        if($hour <= 7 || $hour >= 17){
            return true;
        } else {
            return false;
        }
    }

    /*
    2. Buatlah sebuah fungsi untuk menghitung toleransi waktu login
    */

    private function riskEngine(
        $ipAddress, $macAddress, $rssi, $osType,
        $ipAddressDB, $macAddressDB, $rssiFromDb, $osTypeDB
    ): int {
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
        foreach ($osTypeDB as $osTypeEl) {
            Log::alert("OS Type checking ->" . $osTypeEl->os_type);
            Log::alert("Compare " . $osTypeEl->os_type . " And " . $osType);
            if (str_contains($osType, $osTypeEl->os_type)) {
                Log::alert("Same OS Type");
                $riskValue +=18; break;
            }
        }
        if (!$this->isLoginAbnormal()) {
            $riskValue += 7;
        }
        Log::alert("Risk Factor : " . $riskValue);
        return $riskValue;
    }

    private function detectClientOS(): string {
        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $userAgent = strtolower($userAgent);
        switch ($userAgent) {
            case str_contains($userAgent, "windows"): return "Windows";
            case str_contains($userAgent, "linux"): return "Linux";
            case str_contains($userAgent, "darwin"): return "Mac OS";
            default: return "Mobile Device";
        }
    }

    public function getIpAddress($request): array
    {
        return DB::select('SELECT ip_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }

    public function getMacAddress($request): array
    {
        return DB::select('SELECT mac_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }

    public function getOsType($request): array {
        return DB::select('SELECT os_type FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }
}
