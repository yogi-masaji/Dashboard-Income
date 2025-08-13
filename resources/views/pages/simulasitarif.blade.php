@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $chiselVersion = session('selected_location_chisel_Version', 'Chisel Version Tidak Diketahui');
        $systemCode = session('selected_location_system', 'System Code Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp
    <!DOCTYPE html>
    <html>

    <head>
        <title>Dashboard Simulasi Tarif</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta charset="UTF-8" />
        <!-- Menggunakan CDN untuk Bootstrap dan assets lainnya agar mudah -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body {
                background-color: #343a40;
                color: white;
            }

            .card {
                background-color: #454d55;
                border: 1px solid #6c757d;
            }

            .table-striped tbody tr:nth-of-type(odd) {
                background-color: rgba(255, 255, 255, 0.05);
            }

            .table {
                color: white;
            }

            .form-control {
                background-color: #5a6268;
                color: white;
                border: 1px solid #6c757d;
            }

            .form-control::placeholder {
                color: #ced4da;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid py-3">
            <h4>Simulasi Tarif Parkir</h4>
            <hr style="border-color: white;">

            <div class="card">
                <div class="card-header">
                    <b><i class="fas fa-search"></i> Search</b>
                </div>
                <div class="card-body">
                    <form action="{{ route('tarif.calculate') }}" method="POST">
                        @csrf
                        {{-- Baris 1: Tanggal & Info Tarif Existing --}}
                        <div class="row mb-3">
                            <div class="col-md-2 form-group">
                                <label>From Date</label>
                                <input type="date" class="form-control" name="tgl_awal" required
                                    value="{{ old('tgl_awal', $inputs['tgl_awal'] ?? '') }}">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>To Date</label>
                                <input type="date" class="form-control" name="tgl_akhir" required
                                    value="{{ old('tgl_akhir', $inputs['tgl_akhir'] ?? '') }}">
                            </div>
                            <div class="col-md-2">
                                <label>Tarif Pertama Existing</label>
                                <p class="font-weight-bold">{{ number_format($td01 ?? 0) }}</p>
                            </div>
                            <div class="col-md-2">
                                <label>Tarif Selanjutnya</label>
                                <p class="font-weight-bold">{{ number_format($ts02 ?? 0) }}</p>
                            </div>
                            <div class="col-md-2">
                                <label>Tarif Maximal</label>
                                <p class="font-weight-bold">{{ number_format($max ?? 0) }}</p>
                            </div>
                        </div>

                        {{-- Baris 2: Simulasi 1 --}}
                        <h6><u>Simulasi Pertama</u></h6>
                        <div class="row mb-3">
                            <div class="col-md-12 form-group">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" onclick="toggleProgresif('simulasi1', false)"
                                        type="radio" name="simulasi1_tipe" id="flat1" value="flat">
                                    <label class="form-check-label">Flat</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" onclick="toggleProgresif('simulasi1', true)"
                                        type="radio" name="simulasi1_tipe" id="progresif1" value="progresif" checked>
                                    <label class="form-check-label">Progresif</label>
                                </div>
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Tarif Pertama</label>
                                <input placeholder="Masukkan Tarif" type="number" class="form-control" name="t_awal"
                                    required value="{{ old('t_awal', $inputs['t_awal'] ?? '') }}">
                            </div>
                            <div class="col-md-2 form-group simulasi1-progresif">
                                <label>Masa Berlaku (Menit)</label>
                                <input placeholder="Waktu Dalam Menit" type="number" class="form-control" name="berlaku"
                                    value="{{ old('berlaku', $inputs['berlaku'] ?? '') }}">
                            </div>
                            <div class="col-md-2 form-group simulasi1-progresif">
                                <label>Tarif Selanjutnya</label>
                                <input placeholder="Masukkan Tarif" type="number" class="form-control" name="t_akhir"
                                    value="{{ old('t_akhir', $inputs['t_akhir'] ?? '') }}">
                            </div>
                            <div class="col-md-2 form-group simulasi1-progresif">
                                <label>Tarif Maximal</label>
                                <input placeholder="Masukkan Tarif" type="number" class="form-control" name="t_max"
                                    value="{{ old('t_max', $inputs['t_max'] ?? '') }}">
                            </div>
                        </div>

                        {{-- Baris 3: Simulasi 2 --}}
                        <h6><u>Simulasi Kedua (Opsional)</u></h6>
                        <div class="row mb-3">
                            <div class="col-md-2 form-group">
                                <label>Tarif Pertama</label>
                                <input placeholder="Masukkan Tarif" type="number" class="form-control" name="t_ska"
                                    value="{{ old('t_ska', $inputs['t_ska'] ?? '') }}">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Masa Berlaku (Menit)</label>
                                <input placeholder="Waktu Dalam Menit" type="number" class="form-control" name="berlaku2"
                                    value="{{ old('berlaku2', $inputs['berlaku2'] ?? '') }}">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Tarif Selanjutnya</label>
                                <input placeholder="Masukkan Tarif" type="number" class="form-control" name="t_ske"
                                    value="{{ old('t_ske', $inputs['t_ske'] ?? '') }}">
                            </div>
                            <div class="col-md-2 form-group">
                                <label>Tarif Maksimal</label>
                                <input placeholder="Masukkan Tarif" type="number" class="form-control" name="t_maxs"
                                    value="{{ old('t_maxs', $inputs['t_maxs'] ?? '') }}">
                            </div>
                        </div>

                        {{-- Baris 4: Opsi & Tombol Submit --}}
                        <div class="row">
                            <div class="col-md-2 form-group">
                                <label>GP</label>
                                <select name="gp" class="form-control" required>
                                    <option value="" disabled selected>--Pilih Aksi--</option>
                                    <option value="gp" {{ old('gp', $inputs['gp'] ?? '') == 'gp' ? 'selected' : '' }}>
                                        GP</option>
                                    <option value="ngp"
                                        {{ old('gp', $inputs['gp'] ?? '') == 'ngp' ? 'selected' : '' }}>No GP</option>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <label>Vehicle</label>
                                <select name="select" class="form-control" required>
                                    <option value="" disabled selected>--Pilih Vehicle--</option>
                                    <option value="Mobil"
                                        {{ old('select', $inputs['select'] ?? '') == 'Mobil' ? 'selected' : '' }}>Mobil
                                    </option>
                                    <option value="Motor"
                                        {{ old('select', $inputs['select'] ?? '') == 'Motor' ? 'selected' : '' }}>Motor
                                    </option>
                                    <option value="Truck"
                                        {{ old('select', $inputs['select'] ?? '') == 'Truck' ? 'selected' : '' }}>Truck
                                    </option>
                                    <option value="Taxi"
                                        {{ old('select', $inputs['select'] ?? '') == 'Taxi' ? 'selected' : '' }}>Taxi
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4 align-self-end form-group">
                                <button type="submit" class="btn btn-info">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bagian Hasil --}}
            @if (isset($results) && count($results) > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <b><i class="fas fa-chart-bar"></i> Hasil Simulasi</b>
                    </div>
                    <div class="card-body">
                        @if (empty($t_ska) && empty($t_ske))
                            {{-- Tampilan untuk 1 simulasi --}}
                            @include('partials.hasil-simulasi-1')
                        @else
                            {{-- Tampilan untuk 2 simulasi --}}
                            @include('partials.hasil-simulasi-2')
                        @endif
                    </div>
                </div>
            @elseif(isset($results) && count($results) == 0)
                <div class="alert alert-warning mt-4">
                    <strong>Data tidak ditemukan.</strong> Periksa kembali rentang tanggal dan filter yang Anda pilih.
                </div>
            @endif

        </div>

        <script>
            function toggleProgresif(simulasi, isProgresif) {
                const elements = document.querySelectorAll(`.${simulasi}-progresif`);
                elements.forEach(el => {
                    el.style.display = isProgresif ? 'block' : 'none';
                });
            }
            // Panggil saat load untuk set state awal
            document.addEventListener('DOMContentLoaded', function() {
                toggleProgresif('simulasi1', document.getElementById('progresif1').checked);
            });
        </script>
    </body>

    </html>

    {{-- Buat file baru: resources/views/partials/hasil-simulasi-1.blade.php --}}
    @if (isset($results))
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-center">{{ $inputs['select'] ?? '' }} Tarif Existing</h5>
                    <canvas id="chartExisting"></canvas>
                </div>
                <div class="col-md-6">
                    <h5 class="text-center">{{ $inputs['select'] ?? '' }} Tarif Simulasi</h5>
                    <canvas id="chartSimulasi1"></canvas>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                                <th>Tarif</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td> 1 Jam</td>
                                <td>{{ $tC1 }}</td>
                                <td>{{ number_format($td01) }}</td>
                                <td>{{ number_format($tf_D1) }}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td> 1-2 Jam</td>
                                <td>{{ $tC2 }}</td>
                                <td>{{ number_format($td02) }}</td>
                                <td>{{ number_format($tf_D2) }}</td>
                            </tr>
                            {{-- ... baris lainnya ... --}}
                            <tr>
                                <th colspan="2">Grand Total</th>
                                <th>{{ $tCgt }}</th>
                                <th></th>
                                <th>{{ number_format($sumtd) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kondisi</th>
                                <th>Jumlah</th>
                                <th>Tarif</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td> 1 Jam</td>
                                <td>{{ $tC1 }}</td>
                                <td>{{ number_format($t_awal) }}</td>
                                <td>{{ number_format($tf_C1) }}</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td> 1-2 Jam</td>
                                <td>{{ $tC2 }}</td>
                                <td>{{ number_format($tfC1) }}</td>
                                <td>{{ number_format($tf_C2) }}</td>
                            </tr>
                            {{-- ... baris lainnya ... --}}
                            <tr>
                                <th colspan="2">Grand Total</th>
                                <th>{{ $tCgt }}</th>
                                <th></th>
                                <th>{{ number_format($sumti) }}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-12">
                    <h5 class="text-center">Hasil Perbandingan</h5>
                    <table class="table table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Total Tarif Existing</th>
                                <th>Total Tarif Simulasi 1</th>
                                <th>Selisih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ number_format($sumtd) }}</td>
                                <td>{{ number_format($sumti) }}</td>
                                <td>{{ number_format($tdmti) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartData = {
                    labels: [@json($tglAwal . ' - ' . $tglAkhir)],
                    datasets: [{
                            label: 'Casual < 1 Jam',
                            data: [@json($tf_D1)],
                            backgroundColor: 'rgb(153, 102, 255)'
                        },
                        {
                            label: 'Casual 1-2 Jam',
                            data: [@json($tf_D2)],
                            backgroundColor: 'rgb(54, 162, 235)'
                        },
                        {
                            label: 'Casual 2-3 Jam',
                            data: [@json($tf_D3)],
                            backgroundColor: 'rgb(0,255,255)'
                        },
                        {
                            label: 'Casual 3-4 Jam',
                            data: [@json($tf_D4)],
                            backgroundColor: 'rgb(239,224,86)'
                        },
                        {
                            label: 'Casual 4-5 Jam',
                            data: [@json($tf_D5)],
                            backgroundColor: 'rgb(0,255,0)'
                        },
                        {
                            label: 'Casual > 5 Jam',
                            data: [@json($tf_Dm5)],
                            backgroundColor: 'rgb(75,0,130)'
                        }
                    ]
                };
                new Chart(document.getElementById('chartExisting'), {
                    type: 'bar',
                    data: chartData,
                    options: {
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true
                            }
                        }
                    }
                });

                const chartData2 = {
                    labels: [@json($tglAwal . ' - ' . $tglAkhir)],
                    datasets: [{
                            label: 'Casual < 1 Jam',
                            data: [@json($tf_C1)],
                            backgroundColor: 'rgb(153, 102, 255)'
                        },
                        {
                            label: 'Casual 1-2 Jam',
                            data: [@json($tf_C2)],
                            backgroundColor: 'rgb(54, 162, 235)'
                        },
                        {
                            label: 'Casual 2-3 Jam',
                            data: [@json($tf_C3)],
                            backgroundColor: 'rgb(0,255,255)'
                        },
                        {
                            label: 'Casual 3-4 Jam',
                            data: [@json($tf_C4)],
                            backgroundColor: 'rgb(239,224,86)'
                        },
                        {
                            label: 'Casual 4-5 Jam',
                            data: [@json($tf_C5)],
                            backgroundColor: 'rgb(0,255,0)'
                        },
                        {
                            label: 'Casual > 5 Jam',
                            data: [@json($tf_Cm5)],
                            backgroundColor: 'rgb(75,0,130)'
                        }
                    ]
                };
                new Chart(document.getElementById('chartSimulasi1'), {
                    type: 'bar',
                    data: chartData2,
                    options: {
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true
                            }
                        }
                    }
                });
            });
        </script>
    @endif

    {{-- Buat file baru: resources/views/partials/hasil-simulasi-2.blade.php --}}
    @if (isset($results))
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="text-center">{{ $inputs['select'] ?? '' }} Tarif Existing</h5><canvas
                        id="chartExisting"></canvas>
                </div>
                <div class="col-md-4">
                    <h5 class="text-center">{{ $inputs['select'] ?? '' }} Tarif Simulasi 1</h5><canvas
                        id="chartSimulasi1"></canvas>
                </div>
                <div class="col-md-4">
                    <h5 class="text-center">{{ $inputs['select'] ?? '' }} Tarif Simulasi 2</h5><canvas
                        id="chartSimulasi2"></canvas>
                </div>
            </div>
            {{-- Tabel-tabel hasil diletakkan di sini, mirip dengan hasil-simulasi-1 --}}
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Chart untuk Existing (sama seperti simulasi-1)
                const chartData = {
                    labels: [@json($tglAwal . ' - ' . $tglAkhir)],
                    datasets: [{
                            label: 'Casual < 1 Jam',
                            data: [@json($tf_D1)],
                            backgroundColor: 'rgb(153, 102, 255)'
                        },
                        {
                            label: 'Casual 1-2 Jam',
                            data: [@json($tf_D2)],
                            backgroundColor: 'rgb(54, 162, 235)'
                        },
                        {
                            label: 'Casual > 5 Jam',
                            data: [@json($tf_Dm5)],
                            backgroundColor: 'rgb(75,0,130)'
                        }
                    ]
                };
                new Chart(document.getElementById('chartExisting'), {
                    type: 'bar',
                    data: chartData,
                    options: {
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true
                            }
                        }
                    }
                });

                // Chart untuk Simulasi 1 (sama seperti simulasi-1)
                const chartData2 = {
                    labels: [@json($tglAwal . ' - ' . $tglAkhir)],
                    datasets: [{
                            label: 'Casual < 1 Jam',
                            data: [@json($tf_C1)],
                            backgroundColor: 'rgb(153, 102, 255)'
                        },
                        {
                            label: 'Casual 1-2 Jam',
                            data: [@json($tf_C2)],
                            backgroundColor: 'rgb(54, 162, 235)'
                        },
                        {
                            label: 'Casual > 5 Jam',
                            data: [@json($tf_Cm5)],
                            backgroundColor: 'rgb(75,0,130)'
                        }
                    ]
                };
                new Chart(document.getElementById('chartSimulasi1'), {
                    type: 'bar',
                    data: chartData2,
                    options: {
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true
                            }
                        }
                    }
                });

                // Chart untuk Simulasi 2
                const chartData3 = {
                    labels: [@json($tglAwal . ' - ' . $tglAkhir)],
                    datasets: [{
                            label: 'Casual < 1 Jam',
                            data: [@json($tf_S1)],
                            backgroundColor: 'rgb(153, 102, 255)'
                        },
                        {
                            label: 'Casual 1-2 Jam',
                            data: [@json($tf_S2)],
                            backgroundColor: 'rgb(54, 162, 235)'
                        },
                        {
                            label: 'Casual > 5 Jam',
                            data: [@json($tf_Sm5)],
                            backgroundColor: 'rgb(75,0,130)'
                        }
                    ]
                };
                new Chart(document.getElementById('chartSimulasi2'), {
                    type: 'bar',
                    data: chartData3,
                    options: {
                        scales: {
                            x: {
                                stacked: true
                            },
                            y: {
                                stacked: true
                            }
                        }
                    }
                });
            });
        </script>
    @endif

@endsection
