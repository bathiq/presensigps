<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanIzin;
use DB;
use Hash;
use Redirect;

class PresensiController extends Controller
{
    public function create(Request $request)
    {
        $hari_ini = date('Y-m-d');
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensis')->where('tanggal_presensi', $hari_ini)->where('nik',$nik)->count(); 
        $lok_kantor = DB::table('konfigurasi_lokasis')->where('id',1)->first();
        return view('presensi.create', compact('cek', 'lok_kantor'));
    }

    public function store(Request $request)
    {
        $lok_kantor = DB::table('konfigurasi_lokasis')->where('id',1)->first();
        $lok = explode(",", $lok_kantor->lokasi_kantor);
        $latitude_company = $lok[0];
        $longitude_company = $lok[1];
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date('Y-m-d');
        $jam = date('H:i:s'); 
        $lokasi = $request->lokasi;
        $lokasi_user = explode(",",$lokasi);
        $latitude_user = $lokasi_user[0];
        $longitude_user = $lokasi_user[1];
        $jarak = $this->distance($latitude_company, $longitude_company, $latitude_user, $longitude_user);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensis')->where('tanggal_presensi', $tgl_presensi)->where('nik',$nik)->count();
        if ($cek > 0) {
            $ket = "out";
        }else{
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-" . $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;
        $cek = DB::table('presensis')->where('tanggal_presensi', $tgl_presensi)->where('nik',$nik)->count();
        if ($radius > $lok_kantor->radius) {
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda ".$radius." Meter dari Kantor|radius";
        }else{
            if ($cek > 0) {
                $data_pulang = [
                    'time_out' => $jam,
                    'photo_out' => $fileName,
                    'location_out' => $lokasi
                ];
                $update = DB::table('presensis')->where('tanggal_presensi', $tgl_presensi)->where('nik', $nik)->update($data_pulang);
                if ($update) {
                    echo "success|Terima Kasih, Hati Hati Di Jalan|out";
                    Storage::put($file, $image_base64);
                }else {
                    echo "error|Maaf Gagal Absensi, Hubungi Tim IT|out";
                }
            }else{
                $data = [
                    'nik' => $nik,
                    'tanggal_presensi' => $tgl_presensi,
                    'time_in' => $jam,
                    'photo_in' => $fileName,
                    'location_in' => $lokasi
                ];
                $simpan = DB::table('presensis')->insert($data);
                if ($simpan) {
                    echo "success|Absensi Berhasil, Selamat Bekerja|in";
                    Storage::put($file, $image_base64);
                }else {
                    echo "error|Maaf Gagal Absensi, Hubungi Tim IT|in";
                }
            }
        }
    }

    //Menghitung Jarak
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function edit_profile()
    {   
        // Mengambil Nilai NIK Karyawan Ketika Berhasil Login Ke aplikasi e-presensi
        $get_nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawans')->where('nik', $get_nik)->first();
        return view('presensi.edit_profile', compact('karyawan'));
    }

    public function update_profile(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawans')->where('nik', $nik)->first();
        // memeriksa apakah ada data yang dikirim berupa file
        if ($request->hasFile('foto')) {
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $karyawan->image;
        }
        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'image' => $foto
            ];    
        }else{
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'image' => $foto
            ];
        }

        $update = DB::table('karyawans')->where('nik', $nik)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data berhasil di update']);
        }else{
            return Redirect::back()->with(['error' => 'Data gagal di update']);
        }
    }

    public function history(){
        $month = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('presensi.history', compact('month'));
    }

    public function get_history(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $get_nik = Auth::guard('karyawan')->user()->nik;
        $history = DB::table('presensis')
        ->whereRaw('MONTH(tanggal_presensi)="'. $bulan .'"')
        ->whereRaw('YEAR(tanggal_presensi)="'. $tahun .'"')
        ->where('nik', $get_nik)
        ->orderBy('tanggal_presensi')
        ->get();
        
        return view('presensi.get_history', compact('history'));

    }

    public function izin()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $permission = DB::table('pengajuan_izins')->where('nik', $nik)->get();
        return view('presensi.izin', compact('permission'));
    }

    public function create_permission()
    {
        return view('presensi.create_permission');
    }

    public function store_permission(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan,
            'status_approved' => 0
        ];

        $simpan = DB::table('pengajuan_izins')->insert($data);
        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Berhasil Disimpan']);
        }else{
            return redirect('/presensi/izin')->with(['error' => 'Data Gagal Disimpan']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getpresensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensis')
        ->select('presensis.*','nama_lengkap','dept_name')
        ->join('karyawans','presensis.nik','=','karyawans.nik')
        ->join('departments','karyawans.dept_code','=','departments.dept_code')
        ->where('tanggal_presensi', $tanggal)
        ->get();

        return view('presensi.getpresensi', compact('presensi'));
    }

    public function show_maps(Request $request)
    {
        $id = $request->id;
        $presensi = DB::table('presensis')->where('id', $id)
        ->join('karyawans','karyawans.nik','=','presensis.nik')
        ->first();
        return view('presensi.show_maps', compact('presensi'));
    }

    public function laporan()
    {
        $month = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $karyawan = DB::table('karyawans')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('month', 'karyawan'));
    }

    public function cetak_laporan(Request $request)
    {
        $nik = $request->nik;
        $month = $request->month;
        $year = $request->year;
        $month_name = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $karyawan = DB::table('karyawans')
            ->where('nik', $nik)
            ->join('departments','karyawans.dept_code','=','departments.dept_code')
            ->first();
        $presensi = DB::table('presensis')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tanggal_presensi)="'.$month.'"')
            ->whereRaw('YEAR(tanggal_presensi)="'.$year.'"')
            ->orderBy('tanggal_presensi')
            ->get();

        if (isset($_POST['export_excel'])) {
            $time = date('d-M-Y H:i:s');
            // Fungsi header dengan mengirimkan raw data excel 
            header("Content-type: application/vmd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Laporan Presensi Karyawan $time.xls");
            return view('presensi.cetak_laporan_excel', compact('month','year','month_name', 'karyawan','presensi'));
        }

        return view('presensi.cetak_laporan', compact('month','year','month_name', 'karyawan','presensi'));
    }

    public function rekap()
    {
        $month = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('presensi.rekap', compact('month'));
    }

    public function cetak_rekap(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $month_name = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        $rekap = DB::table('presensis')
        ->selectRaw('presensis.nik, nama_lengkap,
            MAX(IF(DAY(tanggal_presensi) = 1, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_1,
            MAX(IF(DAY(tanggal_presensi) = 2, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_2,
            MAX(IF(DAY(tanggal_presensi) = 3, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_3,
            MAX(IF(DAY(tanggal_presensi) = 4, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_4,
            MAX(IF(DAY(tanggal_presensi) = 5, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_5,
            MAX(IF(DAY(tanggal_presensi) = 6, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_6,
            MAX(IF(DAY(tanggal_presensi) = 7, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_7,
            MAX(IF(DAY(tanggal_presensi) = 8, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_8,
            MAX(IF(DAY(tanggal_presensi) = 9, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_9,
            MAX(IF(DAY(tanggal_presensi) = 10, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_10,
            MAX(IF(DAY(tanggal_presensi) = 11, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_11,
            MAX(IF(DAY(tanggal_presensi) = 12, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_12,
            MAX(IF(DAY(tanggal_presensi) = 13, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_13,
            MAX(IF(DAY(tanggal_presensi) = 14, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_14,
            MAX(IF(DAY(tanggal_presensi) = 15, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_15,
            MAX(IF(DAY(tanggal_presensi) = 16, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_16,
            MAX(IF(DAY(tanggal_presensi) = 17, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_17,
            MAX(IF(DAY(tanggal_presensi) = 18, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_18,
            MAX(IF(DAY(tanggal_presensi) = 19, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_19,
            MAX(IF(DAY(tanggal_presensi) = 20, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_20,
            MAX(IF(DAY(tanggal_presensi) = 21, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_21,
            MAX(IF(DAY(tanggal_presensi) = 22, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_22,
            MAX(IF(DAY(tanggal_presensi) = 23, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_23,
            MAX(IF(DAY(tanggal_presensi) = 24, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_24,
            MAX(IF(DAY(tanggal_presensi) = 25, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_25,
            MAX(IF(DAY(tanggal_presensi) = 26, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_26,
            MAX(IF(DAY(tanggal_presensi) = 27, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_27,
            MAX(IF(DAY(tanggal_presensi) = 28, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_28,
            MAX(IF(DAY(tanggal_presensi) = 29, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_29,
            MAX(IF(DAY(tanggal_presensi) = 30, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_30,
            MAX(IF(DAY(tanggal_presensi) = 31, CONCAT(time_in,"-",IFNULL(time_out,"00:00:00")),"")) as tgl_31')
        ->join('karyawans','presensis.nik','=','karyawans.nik')
        ->whereRaw('MONTH(tanggal_presensi)="'.$month.'"')
        ->whereRaw('YEAR(tanggal_presensi)="'.$year.'"')
        ->groupByRaw('presensis.nik,nama_lengkap')
        ->get();

        if (isset($_POST['export_excel'])) {
            $time = date('d-M-Y H:i:s');
            // Fungsi header dengan mengirimkan raw data excel 
            header("Content-type: application/vmd-ms-excel");
            // Mendefinisikan nama file ekspor "hasil-export.xls"
            header("Content-Disposition: attachment; filename=Rekap Presensi Karyawan $time.xls");
        }

        return view('presensi.cetak_rekap', compact('month','year','month_name','rekap'));
    }

    public function izinsakit(Request $request)
    {
        $query = PengajuanIzin::query();
        $query->select('id','tgl_izin','pengajuan_izins.nik','nama_lengkap','jabatan','status','status_approved','keterangan');
        $query->join('karyawans','pengajuan_izins.nik','=','karyawans.nik');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nik)) {
            $query->where('pengajuan_izins.nik', $request->nik);
        }
        if (!empty($request->employee_name)) {
            $query->where('nama_lengkap','like','%'.$request->employee_name.'%');
        }
        if ($request->status_approved != "") {
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin','DESC');
        $izinsakit = $query->paginate(10);
        $izinsakit->appends($request->all());

        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approved_izinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izinsakit_form = $request->id_izinsakit_form;
        $update = DB::table('pengajuan_izins')->where('id', $id_izinsakit_form)->update([
            'status_approved' => $status_approved
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }

    public function batalkan_izinsakit($id)
    {
        $update = DB::table('pengajuan_izins')->where('id', $id)->update([
            'status_approved' => 0
        ]);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }   
    }

    public function cek_pengajuan_izin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('pengajuan_izins')->where('nik', $nik)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }
}
