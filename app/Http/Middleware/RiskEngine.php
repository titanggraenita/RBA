<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

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
        $rssi = $this->detectAccessPoint();
        $ipAddressDB = $this->getIpAddress($request);
        $macAddressDB = $this->getMacAddress($request);
        $osTypeDB = $this->getOsType($request);
        $rssiFromDB = $this->getLocation($request);
        if (count($macAddressDB) < 1 || count($ipAddressDB) < 1 || count($rssiFromDB) < 1) {
            Log::alert("No Device yet");
            return $next($request);
        }
        $riskEngine = $this->riskEngine(
            $ipAddress, $macAddress, $rssi, $osType, $ipAddressDB, $macAddressDB, $rssiFromDB, $osTypeDB
        );
        Log::alert("Risk engine : " . $riskEngine);
        if ($riskEngine <= 50) {
            return redirect()->route("login");
        } else if ($riskEngine < 75) {
           //return redirect()->route("login");
           return redirect("dashboard/otp")->withInput();
        } else {
            return $next($request);
        }
    }


    public function isLoginAbnormal():bool
    {
        $hour = (int)date('H');
        Log::alert("Hour : " . $hour);
        if($hour <= 7 || $hour >= 9){
            return true;
        } else {
            return false;
        }
    }

    private function riskEngine(
        $ipAddress, $macAddress, $rssi, $osType,
        $ipAddressDB, $macAddressDB, $rssiFromDB, $osTypeDB
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
            Log::alert("Compare " . $macAddressEl->mac_address . " And " . $macAddress);
            if ($macAddressEl->mac_address == $macAddress) {
                Log::alert("Same MAC !");
                $riskValue +=25;
                break;
            }
        }
        foreach ($rssiFromDB as $rssiEl) {
            Log::alert("RSSI Checking -> ". $rssiEl->access_point);
            Log::alert("Compare " . $rssiEl->access_point . " And " . $rssi);
            if (str_contains($rssi, $rssiEl->access_point)) {
                Log::alert("Same RSSI !");
                $riskValue += 10;
                break;
            }
        }
        foreach ($osTypeDB as $osTypeEl) {
            Log::alert("OS Type checking ->" . $osTypeEl->os_type);
            Log::alert("Compare " . $osTypeEl->os_type . " And " . $osType);
            if (str_contains($osType, $osTypeEl->os_type)) {
                Log::alert("Same OS Type");
                $riskValue +=25; 
                break;
            }
        }
        if (!$this->isLoginAbnormal()) {
            $riskValue += 15;
        }
        Log::alert("Risk Factor : " . $riskValue);
        return $riskValue;
    }

    private function detectClientOS(): string {
        $userAgent = $_SERVER["HTTP_USER_AGENT"];
        $userAgent = strtolower($userAgent);
        Log::alert("User agent : " . $userAgent);
        switch ($userAgent) {
            case str_contains($userAgent, "windows nt 10.0"): return "Windows 10";
            case str_contains($userAgent, "windows nt 6.3"): return "Windows 8.1";
            case str_contains($userAgent, "windows nt 6.2"): return "Windows 8";
            case str_contains($userAgent, "windows nt 6.1"): return "Windows 7";
            case str_contains($userAgent, "windows nt 6.0"): return "Windows Vista";
            case str_contains($userAgent, "windows nt 5.1"): return "Windows XP";
            case str_contains($userAgent, "windows nt 5.0"): return "Windows 2000";
            case str_contains($userAgent, "linux"): return "Linux";
            case str_contains($userAgent, "x11"): return "UNIX";
            case str_contains($userAgent, "mac"): return "Mac/iOS";
            default: return "Mobile Device";
        }
    }

    public function guzzle(){
        $client = new Client();
        $res = $client->request('GET', 'http://10.252.209.202/rssi_service.php');
	    return $res->getBody()->getContents();
    }

    private function detectAccessPoint()
    {
        $location = $this->guzzle();
        if (str_contains($location, "ARD3")) {
            return "Gedung D3";
        }

        if (str_contains($location, "ARS2")) {
            return "Gedung Pascasarjana";
        }

        if (str_contains($location, "ARTC")) {
            return "Gedung Training Center";
        }

        return "Gedung D4";
    }

    public function getIpAddress($request): array
    {
        return DB::select('SELECT ip_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }

    public function getMacAddress($request): array
    {
        return DB::select('SELECT mac_address FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }

    public function getOsType($request): array 
    {
        return DB::select('SELECT os_type FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }

    public function getLocation($request): array 
    {
        return DB::select('SELECT access_point FROM users INNER JOIN device_from_users ON user_id=device_from_users.user_id WHERE users.email=?;', [$request->email]);
    }
}

