<?php

namespace App\Http\Controllers\Auth\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Admins;
use Helper;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;


    public function showLinkRequestForm(){
        return view('auth.admin.passwords.email');
    }

    public function sendOtp(Request $request){
        $request->validate([
            'phone' => 'required',
        ]);
        $phone = $request->phone;
        if (Admins::where('phone',$request->phone)->exists()) {
            $otp = random_int(100000, 999999);
            $admin = Admins::where('phone',$request->phone)->first();
            $admin->otp = $otp;
            $admin->save();

            Helper::sendSms($request->phone,'আপনার পাসওয়ার্ড পরিবর্তনের জন্য ও.টি.পি '.$otp);

            return view('auth.admin.passwords.otp', compact('phone'));
        }else{
            return redirect()->back()->with('error', 'Phone number not exist!');
        }
    }


    public function changePasswordWithOtp(Request $request){
        $request->validate([
            'phone' => 'required',
            'otp' => 'required',
            'password' => 'required',
        ]);
        $phone = $request->phone;
        if (Admins::where('phone',$request->phone)->where('otp', $request->otp)->exists()) {
            $admin = Admins::where('phone',$request->phone)->first();
            $admin->otp = NULL;
            $admin->password = Hash::make($request->password);
            $admin->save();
            return redirect()->route('login')->with('success', 'Password successfully changed!');
        }else{
            $error = 'OTP not match!';
            return view('auth.admin.passwords.otp', compact('phone','error'));
            // return redirect()->back()->with('error', 'OTP not match!');
        }
    }

}
