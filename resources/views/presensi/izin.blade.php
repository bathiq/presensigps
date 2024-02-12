@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Izin / Sakit</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        @php
            $messageSuccess = Session::get('success');
            $messageError = Session::get('error');
        @endphp
        <div class="form-group boxed">
            @if (Session::get('success'))
            <div class="alert alert-success">
                {{ $messageSuccess }}
            </div>
            @endif
            @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ $messageError }}
            </div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        @foreach ($permission as $val)
        <ul class="listview image-listview">
            <li>
                <div class="item">
                    <div class="in">
                        <div>
                            {{ date("d-m-Y", strtotime($val->tgl_izin)) }} ({{ $val->status == "s" ? "Sakit" : "Izin" }})<br>
                            <small class="text-muted">{{ $val->keterangan }}</small>
                        </div>
                        @if ($val->status_approved == 0)
                            <span class="badge bg-warning">Waiting</span>
                        @elseif ($val->status_approved == 1)
                            <span class="badge bg-success">Approved</span>
                        @elseif ($val->status_approved == 2)
                            <span class="badge bg-danger">Decline</span>
                        @endif
                    </div>
                </div>
            </li>
        </ul>
        @endforeach
    </div>
</div>
<div class="fab-button bottom-right" style="margin-bottom: 60px">
    <a href="/presensi/create_permission" class="fab">
        <ion-icon name="add-outline"></ion-icon>
    </a>
</div>
@endsection