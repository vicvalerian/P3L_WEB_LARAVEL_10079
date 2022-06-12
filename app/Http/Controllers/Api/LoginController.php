<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pelanggan_10079;
use App\Models\Pegawai_10079;
use App\Models\Driver_10079;
use Validator;

class LoginController extends Controller
{
    public function login(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData,[
            'akun' => 'required',
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);
    
        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        if(strcmp($loginData['akun'], 'Pelanggan') == 0){
            if($pelanggan = Pelanggan_10079::where('email_pelanggan', '=', $loginData['email'])->first()){
                $checkHashedPass = Pelanggan_10079::where('email_pelanggan', '=', $loginData['email'])->first();
                $checkedPass = Hash::check($request->password, $checkHashedPass->password_pelanggan);

                if($checkedPass){
                    $data = Pelanggan_10079::where('email_pelanggan', $request->email)->first();
                    if(strcmp($data->status_pelanggan, 'Belum Verifikasi') == 0){
                        return response([
                            'message' => 'Data Pelanggan Belum Diverifikasi',
                            'data' => null
                        ], 400);
                    } else{
                        return response([
                            'message' => 'Login Sebagai Pelanggan',
                            'data' => $data
                        ], 200);
                    }
                } else {
                    return response([
                        'message' => 'Password Pelanggan Salah',
                        'data' => null
                    ], 400);
                }
            } else{
                return response([
                    'message' => 'Email Pelanggan Tidak Ditemukan',
                    'data' => null
                ], 404);
            }
        } else if(strcmp($loginData['akun'], 'Pegawai') == 0){
            if($pegawai = Pegawai_10079::where('email_pegawai', '=', $loginData['email'])->first()){
                $checkHashedPass = Pegawai_10079::where('email_pegawai', '=', $loginData['email'])->first();
                $checkedPass = Hash::check($request->password, $checkHashedPass->password_pegawai);
                if($checkedPass){
                    $data = Pegawai_10079::where('email_pegawai', $request->email)->first();
                    return response([
                        'message' => 'Login Sebagai Pegawai',
                        'data' => $data
                    ], 200);
                } else {
                    return response([
                        'message' => 'Password Pegawai Salah',
                        'data' => null
                    ], 400);
                }
            } else{
                return response([
                    'message' => 'Email Pegawai Tidak Ditemukan',
                    'data' => null
                ], 404);
            }
        } else if(strcmp($loginData['akun'], 'Driver') == 0){
            if($driver = Driver_10079::where('email_driver', '=', $loginData['email'])->first()){
                $checkHashedPass = Driver_10079::where('email_driver', '=', $loginData['email'])->first();
                $checkedPass = Hash::check($request->password, $checkHashedPass->password_driver);

                if($checkedPass){
                    $data = Driver_10079::where('email_driver', $request->email)->first();
                    return response([
                        'message' => 'Login Sebagai Driver',
                        'data' => $data
                    ], 200);
                } else {
                    return response([
                        'message' => 'Password Driver Salah',
                        'data' => null
                        ], 400);
                }
            } else{
                return response([
                    'message' => 'Email Driver Tidak Ditemukan',
                    'data' => null
                ], 404);
            }
        } else{
            return response([
                'message' => 'Login Gagal',
                'data' => null
            ], 404);
        }
    }

    public function loginMobile(Request $request){
        $loginData = $request->all();
        $validate = Validator::make($loginData,[
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]);
    
        if($validate->fails())
        return response(['message' => $validate->errors()],400);

        if(Pelanggan_10079::where('email_pelanggan', $loginData['email'])->first()){
            $loginPelanggan = Pelanggan_10079::where('email_pelanggan', $loginData['email'])
                                                ->where('status_pelanggan', 'Belum Verifikasi')
                                                ->first();
            
            if($loginPelanggan){
                return response([
                    'message' => 'Data Pelanggan Belum Diverifikasi',
                    'data' => null
                ], 404);
            }

            $checkHashedPass = Pelanggan_10079::where('email_pelanggan', '=', $loginData['email'])->first();
            $checkedPass = Hash::check($request->password, $checkHashedPass->password_pelanggan);

            if($checkedPass){
                $data = Pelanggan_10079::where('email_pelanggan', $request->email)->first();
                return response([
                    'message' => 'Login Sebagai Pelanggan',
                    'data' => $data
                ], 200);
            } else{
                return response([
                    'message' => 'Password Pelanggan Salah',
                    'data' => null
                ], 400);
            }
        } else if(Pegawai_10079::where('email_pegawai', $loginData['email'])->first()){
            $checkHashedPass = Pegawai_10079::where('email_pegawai', '=', $loginData['email'])->first();
            $checkedPass = Hash::check($request->password, $checkHashedPass->password_pegawai);
            if($checkedPass){
                $data = Pegawai_10079::where('email_pegawai', $request->email)->first();
                return response([
                    'message' => 'Login Sebagai Pegawai',
                    'data' => $data
                ], 200);
            } else {
                return response([
                    'message' => 'Password Pegawai Salah',
                    'data' => null
                ], 400);
            }
        } else if(Driver_10079::where('email_driver', '=', $loginData['email'])->first()){
            $checkHashedPass = Driver_10079::where('email_driver', '=', $loginData['email'])->first();
            $checkedPass = Hash::check($request->password, $checkHashedPass->password_driver);

            if($checkedPass){
                $data = Driver_10079::where('email_driver', $request->email)->first();
                return response([
                    'message' => 'Login Sebagai Driver',
                    'data' => $data
                ], 200);
            } else {
                return response([
                    'message' => 'Password Driver Salah',
                    'data' => null
                    ], 400);
            }
        } else{
            return response([
                'message' => 'Login Gagal',
                'data' => null
            ], 404);
        }
    }
}