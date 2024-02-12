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
            font-family: Arial, Helvetica, sans-serif;
        }
        .tabel-presensi tr th{
            border: 1px solid #131212;
            padding: 8px;
            background-color: #dbdbdb;
            font-size: 10px;
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
<body class="A4 landscape">
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
                    REKAP PRESENSI KARYAWAN <br>
                    PERIODE {{ strtoupper($month_name[$month]) }} {{ $year }} <br>
                    PT. Panasea Digital Technology Indonesia <br>
                </span>
                <span><i>Jln. Mangkurejo No. 15, Kecamatan Sedati, Kabupaten Sidoarjo</i></span>
            </td>
        </tr>
    </table>

    <table class="tabel-presensi">
        <tr>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">TH</th>
            <th rowspan="2">TT</th>
        </tr>
        <tr>
            @for ($i = 1; $i <= 31; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
        @foreach ($rekap as $val)
            <tr>
                <td>{{ $val->nik }}</td>
                <td>{{ $val->nama_lengkap }}</td>
                @php
                    $total_hadir = 0;
                    $total_terlambat = 0;
                @endphp
                @for ($i = 1; $i <= 31; $i++)
                @php
                    $tgl = "tgl_".$i;
                    if (empty($val->$tgl)) {
                        $hadir = ['',''];
                        $total_hadir += 0;
                    }else{
                        $hadir = explode("-",$val->$tgl);
                        $total_hadir += 1;
                        if ($hadir[0] > "08:00:00") {
                            $total_terlambat += 1;
                        }
                    }
                @endphp
                <td>
                    <span style="color:{{ $hadir[0] > "08:00:00" ? "red" : "" }}">{{ $hadir[0] }}</span> <br>
                    <span style="color:{{ $hadir[1] < "16:00:00" ? "red" : "" }}">{{ $hadir[1] }}</span>
                </td>
                @endfor
                <td>{{ $total_hadir }}</td>
                <td>{{ $total_terlambat }}</td>
            </tr>
        @endforeach
    </table>

    <table width="100%" style="margin-top: 100px">
        <tr>
            <td></td>
            <td style="text-align: center">Surabaya, {{ date('d-m-Y') }}</td>
        </tr>
        <tr>
            <td style="text-align: center; vertical-align: bottom;" height="100px">
                <u>Panji Galih Anugrah S.Ps</u><br>
                <i><b>HRD Manager</b></i>
            </td>
            <td style="text-align: center; vertical-align: bottom;" height="100px">
                <u>Muhammad Baithul Athiq S.Kom</u><br>
                <i><b>Direktur</b></i>
            </td>
        </tr>
    </table>
  </section>

</body>

</html>