<?php
function DateToIndo2($date2)
{
    $BulanIndo2 = [
        "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"
    ];
    $tahun2 = substr($date2, 0, 4); // memisahkan format tahun menggunakan substring
    $bulan2 = substr($date2, 5, 2); // memisahkan format bulan menggunakan substring
    $tgl2 = substr($date2, 8, 2); // memisahkan format tanggal menggunakan substring

    $result = $tgl2 . " " . $BulanIndo2[(int)$bulan2 - 1] . " " . $tahun2;
    return ($result);
}
function hitungjamterlambat($jadwal_jam_masuk, $jam_presensi){
    $j1 = strtotime($jadwal_jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2-$j1;

    $jamterlambat = floor($diffterlambat / (60*60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat * (60*60)))/60);

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;

    $terlambat = $jterlambat . ":" . $menitterlambat;
    return $terlambat;
}

function hitungjamterlambatdesimal($jam_masuk, $jam_presensi)
{
    $j1 = strtotime($jam_masuk);
    $j2 = strtotime($jam_presensi);

    $diffterlambat = $j2-$j1;

    $jamterlambat = floor($diffterlambat / (60*60));
    $menitterlambat = floor(($diffterlambat - ($jamterlambat *(60*60))) / 60);

    $jterlambat = $jamterlambat <= 9 ? "0" . $jamterlambat : $jamterlambat;
    $mterlambat = $menitterlambat <= 9 ? "0" . $menitterlambat : $menitterlambat;

    $desimalterlambat = ROUND(($menitterlambat/60), 2);
    return $desimalterlambat;
}