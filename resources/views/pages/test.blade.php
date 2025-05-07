@extends('layout.nav')
@section('content')
    @php
        $lokasiName = session('selected_location_name', 'Lokasi Default');
        $ipLokasi = session('selected_location_ip_lokasi', 'IP Tidak Diketahui');
        $lokasiId = session('selected_location_id', 0);
        $lokasiGrup = session('selected_location_id_grup', 'Group Tidak Diketahui');
        $kodeLokasi = session('selected_location_kode_lokasi', 'Kode Tidak Diketahui');
        $chiselVersion = session('selected_location_chisel_Version', 'Chisel Version Tidak Diketahui');
        $navbarTitle = $lokasiName;
    @endphp





    <script>
        const kodeLokasi = @json($kodeLokasi);
    </script>

    <style>
        .nav-custom-tab {
            background-color: #185BB4;
            padding: 10px;
            border-radius: 10px;
        }
    </style>

    <style>
        .custom-nav {
            background-color: #185BB4 !important;
            padding: 10px;
            border-radius: 10px;
            width: 40% !important;
        }

        .nav-pills~.tab-content {
            box-shadow: none !important;
        }

        .tab-content {
            background-color: transparent !important;
            width: 100% !important;
        }

        .dashboard-card {
            background-color: #2A3A5A;
            border-radius: 10px;
            border: 2px solid #7e7e7e;
            padding: 13px;
            margin-bottom: 15px;
        }

        .dashboard-card span {

            font-weight: 800;

        }

        .card-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 5px;
            color: #FFFFFF !important;
        }

        .card-value {
            font-size: 25px;
            font-weight: 700;
            margin-bottom: 0;
            color: #FFFFFF;
        }

        .percentage {
            color: #ff4d4d;
            font-size: 14px;
            font-weight: bold;
        }

        .yesterday {
            font-size: 14px;
            opacity: 0.8;
            margin-top: 5px;
        }

        .tab-content:not(.doc-example-content) {
            /* padding: 0.5rem; */
        }

        .nav-tabs {
            padding: 10px;
            background-color: #003b86;
            border-radius: 10px;
        }

        .content-custom {
            padding: 10px !important;
            background-color: #092953 !important;
            border-radius: 10px !important;
        }
    </style>


    <ul class="nav nav-custom-tab nav-pills mb-3 w-100" id="pills-tab" role="tablist">

        @if (isset($tabMenus['Transaction']))
            <li class="nav-item dropdown flex-fill text-center" role="presentation">
                <button class="nav-link dropdown-toggle active w-100" id="transactionDropdown" data-bs-toggle="dropdown"
                    type="button" aria-expanded="false">
                    Transaction
                </button>
                <ul class="dropdown-menu w-100">
                    <li>
                        <button class="dropdown-item" id="pills-transaction-item1-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-transaction-item1" type="button" role="tab">
                            Transaction Daily
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-transaction-item2-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-transaction-item2" type="button" role="tab">
                            Transaction Weekly
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-transaction-item3-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-transaction-item3" type="button" role="tab">
                            Transaction Monthly
                        </button>
                    </li>
                </ul>
            </li>
        @endif

        @if (isset($tabMenus['Income']))
            <li class="nav-item dropdown flex-fill text-center" role="presentation">
                <button class="nav-link dropdown-toggle w-100" id="incomeDropdown" data-bs-toggle="dropdown" type="button"
                    aria-expanded="false">
                    Income
                </button>
                <ul class="dropdown-menu w-100">
                    <li>
                        <button class="dropdown-item" id="pills-income-item1-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-income-item1" type="button" role="tab">
                            Income Daily
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-income-item2-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-income-item2" type="button" role="tab">
                            Income Weekly
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-income-item3-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-income-item3" type="button" role="tab">
                            Income Monthly
                        </button>
                    </li>
                </ul>
            </li>
        @endif

        @if (isset($tabMenus['E-Payment']))
            <li class="nav-item dropdown flex-fill text-center" role="presentation">
                <button class="nav-link dropdown-toggle w-100" id="epaymentDropdown" data-bs-toggle="dropdown"
                    type="button" aria-expanded="false">
                    E-Payment
                </button>
                <ul class="dropdown-menu w-100">
                    <li>
                        <button class="dropdown-item" id="pills-epayment-item1-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-epayment-item1" type="button" role="tab">
                            E-Payment Daily
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-epayment-item2-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-epayment-item2" type="button" role="tab">
                            E-Payment Weekly
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-epayment-item3-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-epayment-item3" type="button" role="tab">
                            E-Payment Monthly
                        </button>
                    </li>
                </ul>
            </li>
        @endif

        @if (isset($tabMenus['Traffic Management']))
            <li class="nav-item dropdown flex-fill text-center" role="presentation">
                <button class="nav-link dropdown-toggle w-100" id="trafficDropdown" data-bs-toggle="dropdown" type="button"
                    aria-expanded="false">
                    Traffic Management
                </button>
                <ul class="dropdown-menu w-100">
                    <li>
                        <button class="dropdown-item" id="pills-traffic-item1-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-traffic-item1" type="button" role="tab">
                            Traffic Daily
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-traffic-item2-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-traffic-item2" type="button" role="tab">
                            Traffic Weekly
                        </button>
                    </li>
                    <li>
                        <button class="dropdown-item" id="pills-traffic-item3-tab" data-bs-toggle="pill"
                            data-bs-target="#pills-traffic-item3" type="button" role="tab">
                            Traffic Monthly
                        </button>
                    </li>
                </ul>
            </li>
        @endif

    </ul>

    <div class="tab-content" id="pills-tabContent">
        {{-- Transaction Items --}}
        <div class="tab-pane fade show active" id="pills-transaction-item1" role="tabpanel"
            aria-labelledby="pills-transaction-item1-tab">
            @include('tab-content.transactiondaily')
        </div>
        <div class="tab-pane fade" id="pills-transaction-item2" role="tabpanel"
            aria-labelledby="pills-transaction-item2-tab">
            @include('tab-content.transactionweekly')
        </div>
        <div class="tab-pane fade" id="pills-transaction-item3" role="tabpanel"
            aria-labelledby="pills-transaction-item3-tab">
            @include('tab-content.transactionmonthly')
        </div>

        {{-- Income Items --}}
        <div class="tab-pane fade" id="pills-income-item1" role="tabpanel" aria-labelledby="pills-income-item1-tab">
            @include('tab-content.incomedaily')
        </div>
        <div class="tab-pane fade" id="pills-income-item2" role="tabpanel" aria-labelledby="pills-income-item2-tab">
            @include('tab-content.incomeweekly')
        </div>
        <div class="tab-pane fade" id="pills-income-item3" role="tabpanel" aria-labelledby="pills-income-item3-tab">
            @include('tab-content.incomemonthly')
        </div>

        {{-- E-Payment Items --}}
        <div class="tab-pane fade" id="pills-epayment-item1" role="tabpanel" aria-labelledby="pills-epayment-item1-tab">
            @include('tab-content.epaymentdaily')
        </div>
        <div class="tab-pane fade" id="pills-epayment-item2" role="tabpanel" aria-labelledby="pills-epayment-item2-tab">
            @include('tab-content.epaymentweekly')
        </div>
        <div class="tab-pane fade" id="pills-epayment-item3" role="tabpanel" aria-labelledby="pills-epayment-item3-tab">
            @include('tab-content.epaymentmonthly')
        </div>

        {{-- Traffic Items --}}
        <div class="tab-pane fade" id="pills-traffic-item1" role="tabpanel" aria-labelledby="pills-traffic-item1-tab">
            @include('tab-content.trafficdaily')
        </div>
        <div class="tab-pane fade" id="pills-traffic-item2" role="tabpanel" aria-labelledby="pills-traffic-item2-tab">
            @include('tab-content.trafficweekly')
        </div>
        <div class="tab-pane fade" id="pills-traffic-item3" role="tabpanel" aria-labelledby="pills-traffic-item3-tab">
            @include('tab-content.trafficmonthly')
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdowns = document.querySelectorAll('.dropdown-menu .dropdown-item');

            dropdowns.forEach(item => {
                item.addEventListener('click', function() {
                    const parentButton = this.closest('.dropdown').querySelector(
                        '.dropdown-toggle');
                    parentButton.textContent = this.textContent;

                    // Menutup dropdown setelah item dipilih
                    const dropdown = this.closest('.dropdown');
                    const dropdownMenu = dropdown.querySelector('.dropdown-menu');
                    dropdownMenu.classList.remove('show'); // Menutup dropdown secara manual

                    // Memastikan tombol mendapatkan class 'active' pada item yang dipilih
                    parentButton.classList.add('active');
                });
            });
        });
    </script>

    <script>
        const dailyTransactionURL = "{{ route('getDailyTransaction') }}";
        const weeklyTransactionURL = "{{ route('weeklyTransaction') }}";
        const monthlyTransactionURL = "{{ route('monthlyTransaction') }}";
        const dailyIncomeURL = "{{ route('getDailyIncome') }}";
        const weeklyIncomeURL = "{{ route('weeklyIncome') }}";
        const monthlyIncomeURL = "{{ route('monthlyIncome') }}";
        const dailyEpaymentURL = "{{ route('dailyEpayment') }}";
        const weeklyEpaymentURL = "{{ route('weeklyEpayment') }}";
        const monthlyEpaymentURL = "{{ route('monthlyEpayment') }}";
        const dailyEpaymentChart = "{{ route('dailyEpaymentChart') }}";
        const dailyTrafficURL = "{{ route('dailyTraffic') }}";
        const weeklyTrafficURL = "{{ route('weeklyTraffic') }}";
        const monthlyTrafficURL = "{{ route('monthlyTraffic') }}";
        document.querySelectorAll('.dropdown-item[data-bs-toggle="pill"]').forEach(item => {
            item.addEventListener('shown.bs.tab', () => {
                const dropdown = item.closest('.dropdown');
                const toggle = dropdown?.querySelector('.dropdown-toggle');
                if (toggle) {
                    bootstrap.Dropdown.getInstance(toggle)?.hide();
                }
            });
        });
    </script>
    {{-- <script src="js/transaction.js"></script>
    <script src="js/income.js"></script>
    <script src="js/epayment.js"></script>
    <script src="js/traffic.js"></script> --}}
    @vite(['resources/js/transaction.js', 'resources/js/income.js', 'resources/js/epayment.js', 'resources/js/traffic.js'])
@endsection
