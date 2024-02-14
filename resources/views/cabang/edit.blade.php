<form action="/panel/cabang/{{ $cabang->branch_code }}/update" method="POST" id="formCabangEdit">
    @csrf
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-barcode" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7v-1a2 2 0 0 1 2 -2h2" /><path d="M4 17v1a2 2 0 0 0 2 2h2" /><path d="M16 4h2a2 2 0 0 1 2 2v1" /><path d="M16 20h2a2 2 0 0 0 2 -2v-1" /><path d="M5 11h1v2h-1z" /><path d="M10 11l0 2" /><path d="M14 11h1v2h-1z" /><path d="M19 11l0 2" /></svg>
                </span>
                <input type="text" class="form-control" id="branch_code" name="branch_code" placeholder="Kode Cabang" value="{{ $cabang->branch_code }}" readonly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
                </span>
                <input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Nama Cabang" value="{{ $cabang->branch_name }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-map-pin-pin" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M12.783 21.326a2 2 0 0 1 -2.196 -.426l-4.244 -4.243a8 8 0 1 1 13.657 -5.62" /><path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879z" /><path d="M19 18v.01" /></svg>
                </span>
                <input type="text" class="form-control" id="location_branch" name="location_branch" placeholder="Lokasi Cabang" value="{{ $cabang->location_office }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="input-icon mb-3">
                <span class="input-icon-addon">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-radar-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /><path d="M15.51 15.56a5 5 0 1 0 -3.51 1.44" /><path d="M18.832 17.86a9 9 0 1 0 -6.832 3.14" /><path d="M12 12v9" /></svg>
                </span>
                <input type="text" class="form-control" id="radius_branch" name="radius_branch" placeholder="Radius Cabang" value="{{ $cabang->radius }}"">
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-12">
            <div class="form-group">
                <button class="btn btn-primary w-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 14l11 -11" /><path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" /></svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</form>
<script>
    $(function(){
        $('#formCabangEdit').submit(function(){
            var kode_cabang = $('#formCabangEdit').find('#branch_code').val();
            var nama_cabang = $('#formCabangEdit').find('#branch_name').val();
            var lokasi_cabang = $('#formCabangEdit').find('#location_branch').val();
            var radius_cabang = $('#formCabangEdit').find('#radius_branch').val();
            if (kode_cabang == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Kode Cabang Harus Diisi!!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $('#branch_code').focus();
                });
                return false;
            }else if(nama_cabang == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Nama Cabang Harus Diisi!!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $('#branch_name').focus();
                });
                return false;
            }else if(lokasi_cabang == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Lokasi Cabang Harus Diisi!!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $('#location_branch').focus();
                });
                return false;
            }else if(radius_cabang == ""){
                Swal.fire({
                    title: 'Warning!',
                    text: 'Radius Cabang Harus Diisi!!',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $('#radius_branch').focus();
                });
                return false;   
            }
        });
    });
</script>