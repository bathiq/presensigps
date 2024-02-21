<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>A4</title>

  <!-- Normalize or reset CSS with your favorite library -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

  <!-- Load paper.css for happy printing -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

  <!-- Set page size here: A5, A4 or A3 -->
  <!-- Set also "landscape" if you need -->
    <style>
        @page { 
            size: A4 
        }
        #title{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }
        .tabel-data-karyawan{
            margin-top: 40px;
        }
        .tabel-data-karyawan tr td{
            padding: 5px;
        }
        .tabel-presensi{
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .tabel-presensi tr th{
            border: 1px solid #131212;
            padding: 8px;
            background-color: #dbdbdb;
        }
        .tabel-presensi tr td{
            border: 1px solid #131212;
            padding: 5px;
            font-size: 12px;
        }
        .foto{
            width: 40px;
            height: 30px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">
    @php
        function selisih($jam_masuk, $jam_keluar)
            {
                list($h, $m, $s) = explode(":", $jam_masuk);
                $dtAwal = mktime($h, $m, $s, "1", "1", "1");
                list($h, $m, $s) = explode(":", $jam_keluar);
                $dtAkhir = mktime($h, $m, $s, "1", "1", "1");
                $dtSelisih = $dtAkhir - $dtAwal;
                $totalmenit = $dtSelisih / 60;
                $jam = explode(".", $totalmenit / 60);
                $sisamenit = ($totalmenit / 60) - $jam[0];
                $sisamenit2 = $sisamenit * 60;
                $jml_jam = $jam[0];
                return $jml_jam . ":" . round($sisamenit2);
            }
    @endphp
  <!-- Each sheet element should have the class "sheet" -->
  <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
  <section class="sheet padding-10mm">
    <table style="width: 100%">
        <tr>
            <td style="width: 30px">
                <img src="{{ asset('assets/img/logopresensi.png') }}" width="70" height="70" alt="">
            </td>
            <td>
                <span id="title">
                    LAPORAN PRESENSI KARYAWAN <br>
                    PERIODE {{ strtoupper($month_name[$month]) }} {{ $year }} <br>
                    PT. Panasea Digital Technology Indonesia <br>
                </span>
                <span><i>Jln. Mangkurejo No. 15, Kecamatan Sedati, Kabupaten Sidoarjo</i></span>
            </td>
        </tr>
    </table>
    <table class="tabel-data-karyawan">
        <tr>
            <td rowspan="6">
                @php
                    $path = Storage::url('uploads/karyawan/'.$karyawan->image);
                @endphp
                <img src="{{ url($path) }}" width="120px" height="150px" alt="">
            </td>
        </tr>
        <tr>
            <td>NIK</td>
            <td>:</td>
            <td>{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td>Departemen</td>
            <td>:</td>
            <td>{{ $karyawan->dept_name }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>:</td>
            <td>{{ $karyawan->no_hp }}</td>
        </tr>
    </table>
    <table class="tabel-presensi">
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Foto</th>
            <th>Jam Pulang</th>
            <th>Foto</th>
            <th>Keterangan</th>
            <th>Jumlah Jam</th>
        </tr>
        @foreach ($presensi as $val)
        @php
            $path_in = Storage::url('uploads/absensi/'.$val->photo_in);
            $path_out = Storage::url('uploads/absensi/'.$val->photo_out);
            $jam_terlambat = selisih($val->jam_masuk, $val->time_in);
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date("d-m-Y", strtotime($val->tanggal_presensi)) }}</td>
            <td>{{ $val->time_in }}</td>
            <td><img src="{{ url($path_in) }}" class="foto" alt=""></td>
            <td>{{ $val->time_out != null ? $val->time_out : 'Belum Absen' }}</td>
            <td>
                @if ($val->photo_out != null)
                    <img src="{{ url($path_out) }}" class="foto" alt="">
                @else
                    <img class="foto" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRu2XUjKXh-LnMkWDgqaXlVXJ6dJTfLBxIbnQ&usqp=CAU" class="avatar" alt="">
                @endif
            </td>
            <td>
                @if ($val->time_in > $val->jam_masuk)
                Terlambat {{ $jam_terlambat }}
                @else
                Tepat Waktu
                @endif
            </td>
            <td>
                @if ($val->time_out != null)
                @php
                    $jumlah_jam_kerja = selisih($val->time_in, $val->time_out);
                @endphp
                @else
                @php
                    $jumlah_jam_kerja = 0;
                @endphp
                @endif
                {{ $jumlah_jam_kerja }}
            </td>
        </tr>
        @endforeach
    </table>

    <table width="100%" style="margin-top: 100px">
        <tr>
            <td colspan="2" style="text-align: right">Surabaya, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: bottom;" height="100px">
                <u>Panji Galih Anugrah S.Ps</u><br>
                <i><b>HRD Manager</b></i>
            </td>
            <td style="text-align: center; vertical-align: bottom;" height="100px">
                <u>Muhammad Baithul Athiq</u><br>
                <i><b>Direktur</b></i>
            </td>
        </tr>
    </table>
  </section>

</body>

</html>