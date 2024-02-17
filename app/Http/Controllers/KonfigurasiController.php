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
        $jam_kerja = DB::table('jam_kerjas')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jam_kerja', compact('jam_kerja'));
    }

    public function store_working_hours(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        try {
            $data = [
                'kode_jam_kerja' => $kode_jam_kerja,
                'nama_jam_kerja' => $nama_jam_kerja,
                'awal_jam_masuk' => $awal_jam_masuk,
                'jam_masuk' => $jam_masuk,
                'akhir_jam_masuk' => $akhir_jam_masuk,
                'jam_pulang' => $jam_pulang,
            ];
            DB::table('jam_kerjas')->insert($data);
            return Redirect::back()->with(['success' => 'Data Jam Kerja Berhasil Disimpan']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Jam Kerja Gagal Disimpan']);
        }
    }

    public function edit_working_hours(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $jam_kerja = DB::table('jam_kerjas')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        return view('konfigurasi.edit_jam_kerja', compact('jam_kerja'));
    }

    public function update_working_hours(Request $request)
    {
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuk = $request->jam_masuk;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulang = $request->jam_pulang;

        try {
            $data = [
                'nama_jam_kerja' => $nama_jam_kerja,
                'awal_jam_masuk' => $awal_jam_masuk,
                'jam_masuk' => $jam_masuk,
                'akhir_jam_masuk' => $akhir_jam_masuk,
                'jam_pulang' => $jam_pulang,
            ];
            DB::table('jam_kerjas')->where('kode_jam_kerja', $kode_jam_kerja)->update($data);
            return Redirect::back()->with(['success' => 'Data Jam Kerja Berhasil Diupdate']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => 'Data Jam Kerja Gagal Diupdate']);
        }
    }

    public function delete_working_hours($kode_jam_kerja)
    {
        $hapus = DB::table('jam_kerjas')->where('kode_jam_kerja', $kode_jam_kerja)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Jam Kerja Berhasil Dihapus']);
        }else{
            return Redirect::back()->with(['warning' => 'Jam Kerja Gagal Dihapus']);
        }
    }

    public function set_jam_kerja($nik, Request $request)
    {
        $karyawan = DB::table('karyawans')->where('nik', $nik)->first();
        $jam_kerja = DB::table('jam_kerjas')->orderBy('nama_jam_kerja')->get();
        return view('konfigurasi.set_jam_kerja', compact('karyawan', 'jam_kerja'));
    }
}
