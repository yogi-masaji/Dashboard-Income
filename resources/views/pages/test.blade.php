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
        body {
            background-color: #0a1929;
            color: white;
        }

        .nav-tabs {
            border-bottom: none;
            /* background-color: #0d2d5a; */
            border-radius: 10px;
            padding: 10px;
        }

        .nav-tabs .nav-link {
            color: #000000;
            border: 1px solid;
            background-color: #FFF;
            border-radius: 10px;
            padding: 10px 20px;
            margin: 0 5px;
        }

        .nav-tabs .nav-link.active {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }

        .nav-tabs .nav-link:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .dropdown-menu {
            background-color: #f8f9fa;
            border-radius: 10px;
        }

        .dropdown-item {
            padding: 10px 20px;
        }

        .dropdown-item.active {
            background-color: #e9ecef;
            color: #6c7ae0;
        }

        .stat-card {
            background-color: #1a365d;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .big-number {
            font-size: 3rem;
            font-weight: bold;
        }

        .negative-change {
            color: #ff4d4f;
        }

        .data-table {
            background-color: #0d2d5a;
            border-radius: 10px;
            overflow: hidden;
        }

        .data-table th {
            background-color: #0a1929;
            color: white;
            border: none;
            padding: 15px;
        }

        .data-table td {
            border-color: #1a365d;
            padding: 15px;
        }

        .chart-toggle {
            background-color: #0d2d5a;
            border-radius: 10px;
            overflow: hidden;
        }

        .chart-toggle .btn {
            border-radius: 0;
            padding: 10px 30px;
        }

        .chart-toggle .btn.active {
            background-color: #ffc107;
            color: #000;
            font-weight: bold;
        }

        .chart-container {
            background-color: #0d2d5a;
            border-radius: 10px;
            height: 300px;
            position: relative;
        }

        .chart-bar {
            position: absolute;
            bottom: 40px;
            width: 40px;
            background-color: #1890ff;
            border-radius: 5px 5px 0 0;
            text-align: center;
        }

        .chart-bar.casual {
            background-color: #ff4d4f;
        }

        .chart-bar.pass {
            background-color: #1890ff;
        }

        .chart-label {
            position: absolute;
            top: -25px;
            width: 100%;
            text-align: center;
            color: white;
            font-weight: bold;
            background-color: #1890ff;
            border-radius: 5px;
            padding: 2px 0;
        }

        .chart-bar.casual .chart-label {
            background-color: #ff4d4f;
        }

        .dropdown-menu {
            margin-top: 0;
        }
    </style>
    <style>
        .nav-custom-tab {
            background-color: #061933;
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
            background-color: #ffffff;
            border-radius: 10px;
            border: 2px solid #7e7e7e24;
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
            /* background-color: #003b86; */
            border-radius: 10px;
        }

        .content-custom {
            padding: 10px !important;
            background-color: #ffffff !important;
            border-radius: 10px !important;
            box-shadow: 1px -2px 15px -1px rgba(0, 0, 0, 0.28);
        }
    </style>

    <div class="">
        <!-- Main Tabs Navigation -->
        <style>
            /* Mobile optimizations */
            @media (max-width: 767.98px) {
                .nav-tabs-responsive {
                    flex-direction: column;
                    gap: 6px;
                }

                .nav-tabs-responsive .nav-item {
                    width: 100%;
                }

                .nav-tabs-responsive .nav-link {
                    justify-content: space-between;
                    padding: 14px 16px;
                }
            }

            /* Medium screens */
            @media (min-width: 768px) and (max-width: 991.98px) {
                .nav-tabs-responsive .nav-link {
                    padding: 10px 12px;
                    font-size: 0.9rem;
                }
            }
        </style>

        <ul class="nav nav-tabs mb-4 d-flex w-100" id="mainTabs" role="tablist" style="gap: 10px;">
            @if (isset($tabMenus['Transaction']))
                <li class="nav-item dropdown flex-fill text-center" role="presentation">
                    <a class="nav-link active dropdown-toggle w-100" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false">
                        Transaction Daily
                    </a>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item active" href="#transactionDaily" data-bs-toggle="tab">Transaction
                                Daily</a></li>
                        <li><a class="dropdown-item" href="#transactionWeekly" data-bs-toggle="tab">Transaction Weekly</a>
                        </li>
                        <li><a class="dropdown-item" href="#transactionMonthly" data-bs-toggle="tab">Transaction Monthly</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if (isset($tabMenus['Income']))
                <li class="nav-item dropdown flex-fill text-center" role="presentation">
                    <a class="nav-link dropdown-toggle w-100" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false">
                        Income
                    </a>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#incomeDaily" data-bs-toggle="tab">Income Daily</a></li>
                        <li><a class="dropdown-item" href="#incomeWeekly" data-bs-toggle="tab">Income Weekly</a></li>
                        <li><a class="dropdown-item" href="#incomeMonthly" data-bs-toggle="tab">Income Monthly</a></li>
                    </ul>
                </li>
            @endif

            @if (isset($tabMenus['E-Payment']))
                <li class="nav-item dropdown flex-fill text-center" role="presentation">
                    <a class="nav-link dropdown-toggle w-100" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false">
                        E-Payment
                    </a>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#ePaymentDaily" data-bs-toggle="tab">E-Payment Daily</a></li>
                        <li><a class="dropdown-item" href="#ePaymentWeekly" data-bs-toggle="tab">E-Payment Weekly</a></li>
                        <li><a class="dropdown-item" href="#ePaymentMonthly" data-bs-toggle="tab">E-Payment Monthly</a></li>
                    </ul>
                </li>
            @endif

            @if (isset($tabMenus['Traffic Management']))
                <li class="nav-item dropdown flex-fill text-center" role="presentation">
                    <a class="nav-link dropdown-toggle w-100" data-bs-toggle="dropdown" href="#" role="button"
                        aria-expanded="false">
                        Traffic Management
                    </a>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item" href="#trafficDaily" data-bs-toggle="tab">Traffic Daily</a></li>
                        <li><a class="dropdown-item" href="#trafficWeekly" data-bs-toggle="tab">Traffic Weekly</a></li>
                        <li><a class="dropdown-item" href="#trafficMonthly" data-bs-toggle="tab">Traffic Monthly</a></li>
                    </ul>
                </li>
            @endif
        </ul>



        <!-- Tab Content -->
        <div class="tab-content" id="mainTabsContent">
            <!-- Transaction Daily Tab -->
            <div class="tab-pane fade show active" id="transactionDaily" role="tabpanel">
                @include('tab-content.transactiondaily')
            </div>

            <!-- Other Tabs (Just placeholders) -->
            <div class="tab-pane fade" id="transactionWeekly" role="tabpanel">
                @include('tab-content.transactionweekly')
            </div>

            <div class="tab-pane fade" id="transactionMonthly" role="tabpanel">
                @include('tab-content.transactionmonthly')
            </div>

            <!-- Placeholder for other tabs -->
            <div class="tab-pane fade" id="incomeDaily" role="tabpanel">
                @include('tab-content.incomedaily')
            </div>

            <div class="tab-pane fade" id="incomeWeekly" role="tabpanel">
                @include('tab-content.incomeweekly')
            </div>

            <div class="tab-pane fade" id="incomeMonthly" role="tabpanel">
                @include('tab-content.incomemonthly')
            </div>

            <div class="tab-pane fade" id="ePaymentDaily" role="tabpanel">
                @include('tab-content.epaymentdaily')
            </div>

            <div class="tab-pane fade" id="ePaymentWeekly" role="tabpanel">
                @include('tab-content.epaymentweekly')
            </div>

            <div class="tab-pane fade" id="ePaymentMonthly" role="tabpanel">
                @include('tab-content.epaymentmonthly')
            </div>

            <div class="tab-pane fade" id="trafficDaily" role="tabpanel">
                @include('tab-content.trafficdaily')
            </div>

            <div class="tab-pane fade" id="trafficWeekly" role="tabpanel">
                @include('tab-content.trafficweekly')
            </div>

            <div class="tab-pane fade" id="trafficMonthly" role="tabpanel">
                @include('tab-content.trafficmonthly')
            </div>
        </div>
    </div>



    <!-- Custom JavaScript -->
    <script>
        // Initialize dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Handle tab switching from dropdown items
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Get the target tab
                    const targetTab = this.getAttribute('href');

                    // Update dropdown button text
                    const dropdownButton = this.closest('.dropdown').querySelector('.nav-link');
                    dropdownButton.textContent = this.textContent + ' ';

                    // Add dropdown icon back


                    // Activate the tab
                    const tab = new bootstrap.Tab(document.querySelector(`a[href="${targetTab}"]`));
                    tab.show();

                    // Update active states in nav
                    // Hanya hapus .active dari nav-link dalam nav-tabs
                    document.querySelectorAll('#mainTabs .nav-link').forEach(link => {
                        link.classList.remove('active');
                    });
                    dropdownButton.classList.add('active');


                    // Update active states in dropdown
                    document.querySelectorAll('.dropdown-item').forEach(dropItem => {
                        dropItem.classList.remove('active');
                    });
                    this.classList.add('active');
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
    <script src="js/transaction.js"></script>
    <script src="js/income.js"></script>
    <script src="js/epayment.js"></script>
    <script src="js/traffic.js"></script>
@endsection
