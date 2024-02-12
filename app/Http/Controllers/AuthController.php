<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function proses_login(Request $request)
    {
        if (Auth::guard('karyawan')->attempt(['nik'=>$request->nik, 'password'=> $request->password])) {
            return redirect('/dashboard');
        }else{
            return redirect('/')->with(['warning'=>'NIK / Password Salah']);
        }
    }

    public function proses_logout()
    {
        if (Auth::guard('karyawan')->check()) {
            Auth::guard('karyawan')->logout();
            return redirect('/');
            // echo "Anda Log Out";
        }
    }

    public function proses_logout_admin(){
        if (Auth::guard('user')->check()) {
            Auth::guard('user')->logout();
            return redirect('/panel');
            // echo "Anda Log Out";
        }
    }

    public function lihat_password()
    {
        $pass = Hash::make(123);
        echo $pass;
    }

    public function prosesloginadmin(Request $request)
    {
        if (Auth::guard('user')->attempt(['email'=>$request->email, 'password'=> $request->password])) {
            return redirect('/panel/dashboardadmin');
        }else{
            return redirect('/panel')->with(['warning'=>'Username atau Password Salah']);
        }
    }
}
