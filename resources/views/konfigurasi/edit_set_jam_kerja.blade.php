@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
            <!-- Page pre-title -->
                <div class="page-pretitle">
                    Set Jam Kerja
                </div>
                <h2 class="page-title">
                    Set Jam Kerja Karyawan
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Pegawai
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>NIK</th>
                                <td>{{ $karyawan->nik }}</td>
                            </tr>
                            <tr>
                                <th>Nama Karyawan</th>
                                <td>{{ $karyawan->nama_lengkap }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        Setting Jam Kerja
                    </div>
                    <div class="card-body">
                        <form action="/panel/konfigurasi/update_set_jam_kerja" method="POST">
                            @csrf
                            <input type="hidden" name="nik" value="{{ $karyawan->nik }}">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Hari</th>
                                        <th>Jam Kerja</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($set_jam_kerja as $valx)
                                        <tr>
                                            <td>
                                                {{ $valx->hari }}
                                                <input type="hidden" name="hari[]" value="{{ $valx->hari }}">
                                            </td>
                                            <td>
                                                <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                                    <option value="">Pilih Jam Kerja</option>
                                                    @foreach ($jam_kerja as $val)
                                                        <option {{ $val->kode_jam_kerja == $valx->kode_jam_kerja ? 'selected' : '' }} value="{{ $val->kode_jam_kerja }}">{{ $val->nama_jam_kerja }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-primary w-100" type="submit">Update</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        Data Jam Master
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="6" class="text-center">Master Jam Kerja</th>
                                </tr>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Awal Masuk</th>
                                    <th>Jam Masuk</th>
                                    <th>Akhir Masuk</th>
                                    <th>Jam Pulang</th>
                                </tr>
                                <tbody>
                                    @foreach ($jam_kerja as $val)
                                        <tr>
                                            <td>{{ $val->kode_jam_kerja }}</td>
                                            <td>{{ $val->nama_jam_kerja }}</td>
                                            <td>{{ $val->awal_jam_masuk }}</td>
                                            <td>{{ $val->jam_masuk }}</td>
                                            <td>{{ $val->akhir_jam_masuk }}</td>
                                            <td>{{ $val->jam_pulang }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection