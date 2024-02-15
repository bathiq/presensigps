<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class CabangController extends Controller
{
    public function index()
    {
        $cabang = DB::table('cabangs')->orderBy('branch_code')->get();
        return view('cabang.index', compact('cabang'));
    }

    public function store(Request $request)
    {
        $kode_cabang = $request->branch_code;
        $nama_cabang = $request->branch_name;
        $lokasi_cabang = $request->location_branch;
        $radius_cabang = $request->radius_branch;
        try {
            $data = [
                'branch_code' => $kode_cabang,
                'branch_name' => $nama_cabang,
                'location_office' => $lokasi_cabang,
                'radius' => $radius_cabang,
            ];
            DB::table('cabangs')->insert($data);
            return Redirect::back()->with(['success' => 'Data Cabang Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Cabang Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $kode_cabang = $request->branch_code;
        $cabang = DB::table('cabangs')->where('branch_code', $kode_cabang)->first();
        return view('cabang.edit', compact('cabang'));
    }

    public function update($kode_cabang, Request $request)
    {
        $kode_cabang = $request->branch_code;
        $nama_cabang = $request->branch_name;
        $lokasi_cabang = $request->location_branch;
        $radius_cabang = $request->radius_branch;
        try {
            $data = [
                'branch_name' => $nama_cabang,
                'location_office' => $lokasi_cabang,
                'radius' => $radius_cabang,
            ];
            DB::table('cabangs')->where('branch_code', $kode_cabang)->update($data);
            return Redirect::back()->with(['success' => 'Data Cabang Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Cabang Gagal Diupdate']);
        }
    }

    public function delete($kode_cabang)
    {
        $hapus = DB::table('cabangs')->where('branch_code', $kode_cabang)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Cabang Berhasil Dihapus']);
        }else{
            return Redirect::back()->with(['warning' => 'Cabang Gagal Dihapus']);
        }
    }
}
