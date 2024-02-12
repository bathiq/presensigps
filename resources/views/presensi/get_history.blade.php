@if ($history->isEmpty())
    <div class="alert alert-outline-warning">
        <p>Data Belum Tersedia</p>
    </div>   
@endif
@foreach ($history as $val)
<ul class="listview image-listview">
    <li>
        <div class="item">
            @php
                $path = Storage::url('uploads/absensi/'.$val->photo_in);
            @endphp
            <img src="{{ url($path) }}" alt="image" class="image">
            <div class="in">
                <div>
                    {{ date("d-m-Y", strtotime($val->tanggal_presensi)) }} <br>
                </div>
                <span class="badge {{ $val->time_in < "07:00" ? "bg-success" : "bg-danger" }}">
                    {{ $val->time_in }}
                </span>
                <span class="badge bg-primary">
                    {{ $val->time_out }}
                </span>
            </div>
        </div>
    </li>
</ul>
@endforeach