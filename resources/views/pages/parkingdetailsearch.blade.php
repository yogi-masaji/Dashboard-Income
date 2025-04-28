@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp

    <div class="result mt-5">
        <div class="text-center">
            <h5>BCA Membership</h5>
        </div>

        <table id="membershipTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Masuk</th>
                    <th>Tanggal Keluar</th>
                    <th>Nopol</th>
                    <th>Barcode</th>
                    <th>Kendaraan</th>
                    <th>Tarif Parkir</th>
                    <th>Dendalt</th>
                    <th>Post Masuk</th>
                    <th>Post Keluar</th>
                    <th>Bank</th>
                    <th>Shift</th>
                    <th>Status</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection
