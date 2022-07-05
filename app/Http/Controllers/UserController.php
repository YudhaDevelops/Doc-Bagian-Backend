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
        $hrd = User::where('role','2')->get()->count();
        if($hrd < 1){
            return view('Auth.Admin.Register');
        }else{
            return redirect('/login/admin');
        }
    }

    public function register_admin(Request $request){
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
        $user->role = 2;
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

    public function formLogin(){
        return view('Auth.Login');
    }

    public function login(Request $request){
        // logic login pengecekan password dan email 
        $rules = [
            'email'                 => 'required|email',
            'password'              => 'required'
        ];

        $messages = [
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'password.required'     => 'Password wajib diisi',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $data = [
            'email'     => $request->input('email'),
            'password'  => $request->input('password'),
        ];

        Auth::attempt($data);
        dd($data);
        // get data user
        $dataUser = User::where('email',$data->email)->get();

        if (Auth::check()) { 
            // check data user lewat roles
            $role = User::where('role',$dataUser->role)->get();

            //validasi role
            // jika nilai role 0 -> relawan
            if($role < 1 ){
                return view('Relawan.Dashboard')->with('user', 'Selamat datang Relawan');
            }
            
            // jika nilai role 2 -> hrd
            else if($role > 1){
                return view('Admin.Dashboard')->with('user', 'Selamat datang HRD');
            }
            
            // jika nilai role 1 -> admin biasa
            else{
                return view('HRD.Dashboard')->with('user', 'Selamat datang Admin');
            }
            // return redirect()->route('dashboard')->with('user', 'Selamat datang Admin di Aplikasi');
        } else { // false
            //Login Fail
            Session::flash('error', 'Email atau password salah');
            return redirect()->route('login');
        }
        

    }
}
