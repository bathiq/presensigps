<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = date('Y-m-d');
        $month_now = date('m');
        $year_now = date('Y');
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensi_now = DB::table('presensis')->where('tanggal_presensi', $now)->where('nik', $nik)->first();
        $history_month_now = DB::table('presensis')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tanggal_presensi)="'.$month_now.'"')
            ->whereRaw('YEAR(tanggal_presensi)="'.$year_now.'"')
            ->orderBy('tanggal_presensi')
            ->get();

        $rekap_presensi = DB::table('presensis')
            ->selectRaw('COUNT(nik) as jmlHadir, SUM(IF(time_in > "07:00",1,0)) as jmlTerlambat')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tanggal_presensi)="'.$month_now.'"')
            ->whereRaw('YEAR(tanggal_presensi)="'.$year_now.'"')
            ->first(); 

        $leaderboard = DB::table('presensis')
            ->join('karyawans','presensis.nik','=','karyawans.nik')
            ->where('tanggal_presensi', $now)
            ->orderBy('time_in')
            ->get();

        $rekap_permission = DB::table('pengajuan_izins')
            ->selectRaw('SUM(IF(status="i", 1, 0)) as jmlIzin, SUM(IF(status="s", 1, 0)) as jmlSakit')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_izin)="'.$month_now.'"')
            ->whereRaw('YEAR(tgl_izin)="'.$year_now.'"')
            ->where('status_approved', 1)
            ->first();

        return view('dashboard.dashboard', compact('presensi_now','history_month_now','rekap_presensi','leaderboard',
        'rekap_permission'));
    }

    public function dashboardadmin(){
        $hari_ini = date('Y-m-d');
        $rekap_presensi = DB::table('presensis')
            ->selectRaw('COUNT(nik) as jmlHadir, SUM(IF(time_in > "07:00",1,0)) as jmlTerlambat')
            ->where('tanggal_presensi', $hari_ini)
            ->first(); 

        $rekap_permission = DB::table('pengajuan_izins')
            ->selectRaw('SUM(IF(status="i", 1, 0)) as jmlIzin, SUM(IF(status="s", 1, 0)) as jmlSakit')
            ->where('tgl_izin', $hari_ini)
            ->where('status_approved', 1)
            ->first();

        return view('dashboard.dashboardadmin', compact('rekap_presensi', 'rekap_permission'));
    }
}
