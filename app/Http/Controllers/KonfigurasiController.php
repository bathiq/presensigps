<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KonfigurasiController extends Controller
{
    public function lokasi_kantor()
    {
        $lok_kantor = DB::table('konfigurasi_lokasis')->where('id',1)->first();
        return view('konfigurasi.lokasi_kantor', compact('lok_kantor'));
    }

    public function update_lokasi_kantor(Request $request)
    {
        $lokasi_kantor = $request->lokasi_kantor;
        $radius = $request->radius;

        $update = DB::table('konfigurasi_lokasis')->where('id', 1)->update([
            'lokasi_kantor' => $lokasi_kantor,
            'radius' => $radius,
        ]);

        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function jam_kerja()
    {
        return view('konfigurasi.jam_kerja');
    }
}
