<style>
    #map { height: 250px; }
</style>
<div id="map"></div>
<script>
    var lokasi = "{{ $presensi->location_in }}";
    var loc = lokasi.split(",");
    var latitude = loc[0];
    var longitude = loc[1];
    var map = L.map('map').setView([latitude, longitude], 13);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);
    var marker = L.marker([latitude, longitude]).addTo(map);
    var circle = L.circle([-7.3974787, 112.750954], {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5,
        radius: 1400
    }).addTo(map);
    var popup = L.popup()
        .setLatLng([latitude, longitude])
        .setContent("{{ $presensi->nama_lengkap }}")
        .openOn(map);
</script>