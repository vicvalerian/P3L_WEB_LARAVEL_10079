<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Driver_10079;
use App\Models\Detail_Transaksi_10079;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DriverController extends Controller
{
    public function index(){
        $driver = Driver_10079::all();

        if(count($driver)>0){
            return response ([
                'message' => 'Retrieve All Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function driverByStatus(){
        $status = 'Tersedia';
        $driver = Driver_10079::where('status_driver', $status)->get();

        if(count($driver)>0){
            return response ([
                'message' => 'Retrieve All Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function driverByTransaksi(){
        $driver = DB::table('driver_10079s') 
                        ->join('detail__transaksi_10079s', 'detail__transaksi_10079s.id_driver', '=', 'driver_10079s.id_driver')
                        ->select('driver_10079s.*', 'detail__transaksi_10079s.id_detail_transaksi', 'detail__transaksi_10079s.rating_driver_transaksi')
                        ->orderBy('driver_10079s.id_driver', 'asc')
                        ->get();

        if(count($driver)>0){
            return response ([
                'message' => 'Retrieve All Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function show($id_driver){
        $driver = Driver_10079::where('id_driver', $id_driver)->first();

        if(!is_null($driver)){
            return response ([
                'message' => 'Retrieve Driver Success',
                'data' => $driver
            ],200);
        }

        return response([
            'message' => 'Driver Not Found',
            'data' => null
        ],404);
    }

    public function store (Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'nama_driver' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_driver' => 'required',
            'tgl_lahir_driver' => 'required|date:ymd',
            'jenis_kelamin_driver' => 'required',
            'bahasa_driver' => 'required',
            'foto_driver' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'notelp_driver' => 'required|numeric|digits_between:0,13|starts_with:08',
            'email_driver' => 'required|email:rfc,dns|unique:driver_10079s',
            'sewa_harian_driver' => 'required',
            'status_driver' => 'required',
            'rating_driver' => 'nullable',
            'sim_driver' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'surat_bebas_napza' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'surat_jiwa_jasmani' => 'required|max:1024|mimes:jpg,png,jpeg|image',
            'skck_driver' => 'required|max:1024|mimes:jpg,png,jpeg|image'
        ]);

        $get_data = Driver_10079::orderBy('created_at','DESC')->first();
        if(is_null($get_data)) {
            $id_driver = 'DRV-'.date('dmy').sprintf('%03d', 1);
        } else {
            $find = substr($get_data->id_driver, -3);
            $increment = $find + 1;
            $id_driver = 'DRV-'.date('dmy').sprintf('%03d', $increment);
        }   

        $uploadFotoDriver = $request->foto_driver->store('img_driver', ['disk' => 'public']);
        $uploadSimDriver = $request->sim_driver->store('img_sim_driver', ['disk' => 'public']);
        $uploadSuratNapza = $request->surat_bebas_napza->store('img_napza_driver', ['disk' => 'public']);
        $uploadSuratJiwaJasmani = $request->surat_jiwa_jasmani->store('img_jasmani_driver', ['disk' => 'public']);
        $uploadSkckDriver = $request->skck_driver->store('img_skck_driver', ['disk' => 'public']);

        $passwordDriver = Hash::make($request->tgl_lahir_driver);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $driver = Driver_10079::create([
            'id_driver' => $id_driver,
            'nama_driver' => $request->nama_driver,
            'alamat_driver' => $request->alamat_driver,
            'tgl_lahir_driver' => $request->tgl_lahir_driver,
            'jenis_kelamin_driver' => $request->jenis_kelamin_driver,
            'bahasa_driver' => $request->bahasa_driver,
            'foto_driver' => $uploadFotoDriver,
            'notelp_driver' => $request->notelp_driver,
            'email_driver' => $request->email_driver,
            'sewa_harian_driver' => $request->sewa_harian_driver,
            'status_driver' => $request->status_driver,
            'rating_driver' => $request->rating_driver,
            'password_driver' => $passwordDriver,
            'sim_driver' => $uploadSimDriver,
            'surat_bebas_napza' => $uploadSuratNapza,
            'surat_jiwa_jasmani' => $uploadSuratJiwaJasmani,
            'skck_driver' => $uploadSkckDriver,
        ]);
        return response([
            'message' => 'Add driver Success',
            'data' => $driver
        ],200);
    }

    public function destroy($id_driver){
        $driver = Driver_10079::where('id_driver', $id_driver)->first();

        if (is_null($driver)) {
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ],404);
        }

        if($driver->delete()) {
            return response([
                'message' => 'Delete Driver Success',
                'data' => $driver
            ],200);
        }

        return response([
            'message' => 'Delete Driver Failed',
            'data' => null,
        ],400);
    }

    public function update(Request $request, $id_driver){
        $driver = Driver_10079::where('id_driver', $id_driver)->first();
        if(is_null($driver)){
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_driver' => 'required|regex:/^[\pL\s]+$/u',
            'alamat_driver' => 'required',
            'tgl_lahir_driver' => 'required|date',
            'jenis_kelamin_driver' => 'required',
            'bahasa_driver' => 'required',
            'foto_driver' => 'max:1024|mimes:jpg,png,jpeg|image',
            'notelp_driver' => 'required|numeric|digits_between:0,13|starts_with:08',
            'email_driver' => ['required', 'email:rfc,dns', Rule::unique('driver_10079s')->ignore($driver)],
            'sewa_harian_driver' => 'required',
            'sim_driver' => 'max:1024|mimes:jpg,png,jpeg|image',
            'surat_bebas_napza' => 'max:1024|mimes:jpg,png,jpeg|image',
            'surat_jiwa_jasmani' => 'max:1024|mimes:jpg,png,jpeg|image',
            'skck_driver' => 'max:1024|mimes:jpg,png,jpeg|image',
            'status_driver' => 'required',
            'password_driver' => 'nullable'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $totalRating = DB::table('detail__transaksi_10079s')
                            ->where('id_driver', $id_driver)
                            ->sum('rating_driver_transaksi');
        $jumlahRating = DB::table('detail__transaksi_10079s')
                            ->where('id_driver', $id_driver)
                            ->count('rating_driver_transaksi');
        
        if($jumlahRating == 0){
            $ratingDriver = null;
        } else{
            $ratingDriver = $totalRating / $jumlahRating;
        }

        $driver->nama_driver = $updateData['nama_driver'];
        $driver->alamat_driver = $updateData['alamat_driver'];
        $driver->tgl_lahir_driver = $updateData['tgl_lahir_driver'];
        $driver->jenis_kelamin_driver = $updateData['jenis_kelamin_driver'];
        $driver->bahasa_driver = $updateData['bahasa_driver'];

        if(isset($request->foto_driver)){
            $uploadFotoDriver = $request->foto_driver->store('img_driver', ['disk' => 'public']);
            $driver->foto_driver = $uploadFotoDriver;
        }

        if(isset($request->sim_driver)){
            $uploadSimDriver = $request->sim_driver->store('img_driver', ['disk' => 'public']);
            $driver->sim_driver = $uploadSimDriver;
        }

        if(isset($request->surat_bebas_napza)){
            $uploadNapzaDriver = $request->surat_bebas_napza->store('img_driver', ['disk' => 'public']);
            $driver->surat_bebas_napza = $uploadNapzaDriver;
        }

        if(isset($request->surat_jiwa_jasmani)){
            $uploadJasmaniDriver = $request->surat_jiwa_jasmani->store('img_driver', ['disk' => 'public']);
            $driver->surat_jiwa_jasmani = $uploadJasmaniDriver;
        }

        if(isset($request->skck_driver)){
            $uploadSkckDriver = $request->skck_driver->store('img_driver', ['disk' => 'public']);
            $driver->skck_driver = $uploadSkckDriver;
        }

        $driver->notelp_driver = $updateData['notelp_driver'];
        $driver->email_driver = $updateData['email_driver'];
        $driver->sewa_harian_driver = $updateData['sewa_harian_driver'];
        $driver->status_driver = $updateData['status_driver'];
        $driver->rating_driver = $ratingDriver;

        if(isset($request->password_driver)){
            $updateData['password_driver'] = bcrypt($request->password_driver);
            $driver->password_driver = $updateData['password_driver'];
        }

        if($driver->save()){
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Update Driver Success',
            'data' => null,
        ], 400);
    }

    public function updateDriverMobile(Request $request, $id_driver){
        $driver = Driver_10079::where('id_driver', $id_driver)->first();
        if(is_null($driver)){
            return response([
                'message' => 'Driver Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_driver' => 'nullable|regex:/^[\pL\s]+$/u',
            'alamat_driver' => 'nullable',
            'tgl_lahir_driver' => 'nullable|date',
            'jenis_kelamin_driver' => 'nullable',
            'bahasa_driver' => 'nullable',
            'notelp_driver' => 'nullable|numeric|digits_between:0,13|starts_with:08',
            'email_driver' => ['nullable', 'email:rfc,dns', Rule::unique('driver_10079s')->ignore($driver)],
            'status_driver' => 'nullable',
            'password_driver' => 'nullable'
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $driver->nama_driver = $updateData['nama_driver'];
        $driver->alamat_driver = $updateData['alamat_driver'];
        $driver->tgl_lahir_driver = $updateData['tgl_lahir_driver'];
        $driver->jenis_kelamin_driver = $updateData['jenis_kelamin_driver'];
        $driver->bahasa_driver = $updateData['bahasa_driver'];
        $driver->notelp_driver = $updateData['notelp_driver'];
        $driver->email_driver = $updateData['email_driver'];
        $driver->status_driver = $updateData['status_driver'];

        if(isset($request->password_driver)){
            $updateData['password_driver'] = bcrypt($request->password_driver);
            $driver->password_driver = $updateData['password_driver'];
        }

        if($driver->save()){
            return response([
                'message' => 'Update Driver Success',
                'data' => $driver
            ], 200);
        }

        return response([
            'message' => 'Update Driver Success',
            'data' => null,
        ], 400);
    }
}
