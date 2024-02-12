@foreach ($presensi as $val)
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
@php
    $foto_in = Storage::url('uploads/absensi/'.$val->photo_in); 
    $foto_out = Storage::url('uploads/absensi/'.$val->photo_out); 
@endphp
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $val->nik }}</td>
    <td>{{ $val->nama_lengkap }}</td>
    <td>{{ $val->dept_name }}</td>
    <td>{{ $val->time_in }}</td>
    <td><img src="{{ url($foto_in) }}" class="avatar" alt=""></td>
    <td>{!! $val->time_out != null ? $val->time_out : '<span class="badge text-white bg-danger">Belum Absen</span>' !!}</td>
    <td>
        @if ($val->photo_out != null)
            <img src="{{ url($foto_out) }}" class="avatar" alt="">
        @else
        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRu2XUjKXh-LnMkWDgqaXlVXJ6dJTfLBxIbnQ&usqp=CAU" class="avatar" alt="">
        @endif
    </td>
    <td>
        @if ($val->time_in >= '08:00')
        @php
            $jam_terlambat = selisih('08:00:00', $val->time_in);
        @endphp
            <span class="badge text-white bg-danger">Terlambat {{ $jam_terlambat }}</span>
        @else
            <span class="badge text-white bg-success">Tepat Waktu</span>
        @endif
    </td>
    <td>
        <a href="#" class="btn btn-primary show_maps" id="{{ $val->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" /><path d="M9 4v13" /><path d="M15 7v5.5" /><path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" /><path d="M19 18v.01" /></svg>
        </a>
    </td>
</tr>
@endforeach
<script>
    $(function(){
        $(".show_maps").click(function(e){
            var id = $(this).attr("id");
            $.ajax({
                type: 'POST',
                url: '/panel/show_maps',
                data: {
                    _token:"{{ csrf_token() }}",
                    id: id
                },
                cache:false,
                success:function(respond){
                    $('#loadmaps').html(respond);
                }
            });
            $("#modal-showmaps").modal("show");
        });
    });
</script>