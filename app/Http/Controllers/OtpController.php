<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Otp as ModelsOtp;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    // minta email dapat otp
    public function requestForOtp(Request $request)
    {
        $request = $request->old();
        $email = $request["email"];
        $password = $request["password"];
        Cookie::queue("email", $email, 10);
        Cookie::queue("password", $password, 10);
        return view('otp', [
            'email' => $request['email']
        ]);
    }

    private function generateOtp(): string 
    {
        $length = 6;
        $otpString = "";
        for ($i=0; $i < $length; $i++) { 
            $otpString .= rand(0, 9);
        }
        return $otpString;
    }

    // minta otp return cocokin otp cocok kagak sama yang di db
    public function handleOtpRequest(Request $request)
    {
        $email = $request->email;
        $generatedOtp = $this->generateOtp();
        $response = Http::post('http://35.247.138.126:8080/email', [
            "senderName" => "RBA Admin",
            "senderMail" => "ekharisma@outlook.com",
            "subject" => "RBA OTP",
            "receiverName" => "RBA User",
            "receiverMail" => $email,
            "content" => $generatedOtp
        ]);

        $otpModel = new ModelsOtp;
        $otpModel->email = $email;
        $otpModel->otp = $generatedOtp;
        $otpModel->save();

        return view("verifyOtp");
    }

    public function verifyOtpRequest(Request $request)
    {
        $otp = $request->otp;
        $otpDb = ModelsOtp::where("otp", $otp)->first();
        if ($otpDb != null) {
            $otpDb->delete();
            $email = Cookie::get("email");
            $password = Cookie::get("password");
            $this->authenticate($email, $password);
            $request->session()->regenerate();
            
            return redirect()->intended(RouteServiceProvider::HOME);
        }
        return view("auth.login");
    }

    private function authenticate($email, $password) {
        $params = [
            "email" => $email,
            "password" => $password
        ];
        if (! Auth::attempt($params)) {
            throw ValidationException::withMessages([
                "email" => __("auth.failed")
            ]);
        }
    }
}
