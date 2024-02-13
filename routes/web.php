<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/lihat_password', [AuthController::class, 'lihat_password']);
//User
Route::middleware(['guest:user'])->group(function(){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});
// Karyawan
Route::middleware(['guest:karyawan'])->group(function(){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');
    Route::post('/proses_login', [AuthController::class, 'proses_login']);
});

Route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proses_logout', [AuthController::class, 'proses_logout']);

    // Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);

    // Edit Profile
    Route::get('/edit_profile', [PresensiController::class, 'edit_profile']);
    Route::post('/presensi/{nik}/update_profile', [PresensiController::class, 'update_profile']);

    // History
    Route::get('/presensi/history', [PresensiController::class, 'history']);
    Route::post('/get_history', [PresensiController::class, 'get_history']);

    // Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/create_permission', [PresensiController::class, 'create_permission']);
    Route::post('/presensi/store_permission', [PresensiController::class, 'store_permission']);
    Route::post('/presensi/cek_pengajuan_izin', [PresensiController::class, 'cek_pengajuan_izin']);

});

Route::middleware(['auth:user'])->group(function(){
    Route::get('/proses_logout_admin', [AuthController::class, 'proses_logout_admin']);
    Route::get('/panel/dashboardadmin',[DashboardController::class, 'dashboardadmin']);
    
    //Karyawan
    Route::get('karyawan',[KaryawanController::class, 'index']);
    Route::post('/karyawan/store',[KaryawanController::class, 'store']);
    Route::post('/karyawan/edit',[KaryawanController::class, 'edit']);
    Route::post('/karyawan/{nik}/update',[KaryawanController::class, 'update']);
    Route::post('/karyawan/{nik}/delete',[KaryawanController::class, 'delete']);

    //Departemen
    Route::get('/department',[DepartmentController::class, 'index']);
    Route::post('/department/store',[DepartmentController::class, 'store']);
    Route::post('/department/edit',[DepartmentController::class, 'edit']);
    Route::post('/department/{dept_code}/update',[DepartmentController::class, 'update']);
    Route::post('/department/{dept_code}/delete',[DepartmentController::class, 'delete']);

    //Presensi
    Route::get('/panel/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/panel/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/panel/show_maps', [PresensiController::class, 'show_maps']);
    Route::get('/panel/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/panel/presensi/cetak_laporan', [PresensiController::class, 'cetak_laporan']);
    Route::get('/panel/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/panel/presensi/cetak_rekap', [PresensiController::class, 'cetak_rekap']);
    Route::get('/panel/presensi/izinsakit', [PresensiController::class, 'izinsakit']);
    Route::post('/panel/presensi/approved_izinsakit', [PresensiController::class, 'approved_izinsakit']);
    Route::get('/panel/presensi/{id}/batalkan_izinsakit', [PresensiController::class, 'batalkan_izinsakit']);

    //Konfigurasi
    Route::get('/panel/konfigurasi/lokasi_kantor', [KonfigurasiController::class, 'lokasi_kantor']);
    Route::post('/panel/konfigurasi/update_lokasi_kantor', [KonfigurasiController::class, 'update_lokasi_kantor']);
    Route::get('/panel/konfigurasi/jam_kerja', [KonfigurasiController::class, 'jam_kerja']);
});
