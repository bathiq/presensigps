@extends('layouts.presensi')
@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">History Presensi</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
@endsection
@section('content')
    <div class="row" style="margin-top: 70px"></div>
    <div class="col">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : ''}}>{{ $month[$i] }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Tahun</option>
                        @php
                            $year_start = 2022;
                            $year_now = date("Y");
                        @endphp
                        @for ($year= $year_start; $year <= $year_now; $year++)
                            <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : ''}}>{{ $year }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button class="btn btn-primary btn-block" id="filter"><ion-icon name="search-outline"></ion-icon> Search</button>
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="row"> --}}
        <div class="col" id="show_history">

        </div>
    {{-- </div> --}}
@endsection

@push('myscript')
    <script>
        $(function(){
            $('#filter').click(function(e){
                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();
                $.ajax({
                    type:'POST',
                    url:'/get_history',
                    data:{
                        _token : "{{ csrf_token() }}",
                        bulan : bulan,
                        tahun : tahun
                    },
                    cache : false,
                    success : function(response){
                        $('#show_history').html(response);
                    }
                });
            });
        });
    </script>
@endpush