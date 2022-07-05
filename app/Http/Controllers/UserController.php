<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Session;

/*
catatan role
0 untuk relawan
1 untuk admin biasa
2 untuk admin hrd
*/ 
class UserController extends Controller
{
    public function formRegis_relawan(){
        return view('Auth.Relawan.Register');
    }
    

    public function register_relawan(Request $request){

        // dd($request);
        $rules = [
            'nama_lengkap'          => 'required|min:3|max:64',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|confirmed'
        ];
  
        $messages = [
            'nama_lengkap.required'         => 'Nama Lengkap wajib diisi',
            'nama_lengkap.min'              => 'Nama lengkap minimal 3 karakter',
            'nama_lengkap.max'              => 'Nama lengkap maksimal 64 karakter',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
            'password.required'     => 'Password wajib diisi',
            'password.confirmed'    => 'Password tidak sama dengan konfirmasi password'
        ];
  
        $validator = Validator::make($request->all(), $rules, $messages);
  
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }
  
        $user = new User;
        $user->name = ucwords(strtolower($request->name));
        $user->email = strtolower($request->email);
        $user->password = Hash::make($request->password);
        $user->role = 1;
        $user->email_verified_at = \Carbon\Carbon::now();
        $simpan = $user->save();
  
        if($simpan){
            Session::flash('success', 'Register berhasil! Silahkan login untuk mengakses data');
            // return redirect()->route('login');
            return redirect()->back();
        } else {
            Session::flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            // return redirect()->route('register');
            return redirect()->back();
        }
    }

    public function formRegis_admin(){
        $hrd = User::where('role','2')->get();
        dd($hrd);
        if(''){

        }
    }

    public function register_admin(Request $request){
        dd($request);
    }

    public function formLog_admin(){
        return view('Auth.Admin.Login');
    }

    public function login_admin(Request $request){
        dd($request);
    }
}
