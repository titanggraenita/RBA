<?php

namespace App\Http\Controllers;

use App\Models\Otp as ModelsOtp;
use Illuminate\Http\Request;
use Ichtrojan\Otp\Otp;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{
    // minta email dapat otp
    public function requestForOtp(Request $request)
    {
        $request = $request->old();
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

    public function verifyOtp(Request $request)
    {
        $otp = $request->otp;
        $otpDb = ModelsOtp::where("otp", $otp)->first();
        if ($otpDb != null) {
            $otpDb->delete();
            return view("dashboard");
        }
        return view("auth.login");
    }
}
