<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="vendor/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard Income</title>
    <link rel="stylesheet" href="css/main.css">
    <meta name="description" content="" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="vendor/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <link rel="stylesheet" href="vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="vendor/css/theme-default.css" class="template-customizer-theme-css" />
    {{-- <link rel="stylesheet" href="vendor/css/demo.css" /> --}}

    {{-- DATE RANGE --}}
    {{-- DATE RANGE --}}
    <!-- Vendors CSS -->
    {{-- <link rel="stylesheet" href="vendor/libs/perfect-scrollbar/perfect-scrollbar.css" /> --}}

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    {{-- <script src="vendor/js/config.js"></script> --}}

    @stack('scripts')
</head>

<body>
    <style>
        a.menu-link::before {
            background-color: #fff !important;
        }

        .btn-submit {
            background-color: #fcb900;
            border: none;
            font-weight: 600;
            padding: 8px 20px;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #ffa600;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(222, 215, 84, 0.212);
        }

        /* Ensure that the sidebar has a transition */
        .layout-menu {
            transition: transform 0.3s ease, opacity 0.3s ease;
            will-change: transform, opacity;
        }

        /* Add styles for when the sidebar is toggled */
        .layout-menu-collapsed .layout-menu {
            transform: translateX(-100%);
            opacity: 0;
        }

        .layout-menu .layout-menu-toggle {
            transition: transform 0.3s ease;
        }

        /* Dark Mode Styles */
    </style>
    <style>
        /* When navbar is collapsed */
        .layout-menu-collapsed .layout-menu {
            width: 0 !important;
            overflow: hidden;
        }

        .layout-menu-collapsed .layout-menu .menu-inner>li>a .text-truncate {
            display: none !important;
        }

        .layout-menu-collapsed .layout-menu .app-brand-logo img {
            max-width: 40px;
            height: auto;
        }

        /* When navbar is open */
        .layout-page {
            margin-left: 0px;
            /* No margin when the navbar is open */
            width: calc(100% - 0px);
            /* Content takes the remaining space after the navbar */
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* When navbar is collapsed */
        .layout-menu-collapsed .layout-page {
            margin-left: 0 !important;
            /* Remove any margin */
            width: 100% !important;
            /* Ensure it takes full width */
            padding-left: 0 !important;
            /* Ensure no padding is applied */
        }

        /* Ensure the body or the container around layout-page also has full width */
        body,
        .layout-wrapper {
            width: 100% !important;
            /* Ensure the parent containers are also taking full width */
            margin: 0 !important;
            /* Remove any margin */
        }
    </style>

    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo mt-3">
                    <a href="/" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="/storage/logo.png" alt="logo" height="60">
                        </span>

                    </a>

                    <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
                    </a>
                </div>



                <div class="menu-inner-shadow"></div>
                <form method="POST" action="{{ route('set.location') }}">
                    @csrf
                    <div class="p-5">
                        <h5 class="brand-title">{{ session('selected_location_name', 'GRAND INDONESIA') }}</h5>

                        <label for="locationSelect" class="select-label">Select Location</label>
                        <select class="form-select" name="location_id" id="locationSelect">
                            @foreach ($locations as $lokasi)
                                <option value="{{ $lokasi->id_Lokasi }}"
                                    {{ session('selected_location_id') == $lokasi->id_Lokasi ? 'selected' : '' }}>
                                    {{ $lokasi->nama_Lokasi }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-submit mt-2" style="color:#fff">Submit</button>
                    </div>
                </form>

                <div style="max-height: calc(100vh - 100px); overflow-y: auto;overflow-x: hidden;">
                    <ul class="menu-inner py-1">
                        <!-- Dashboards -->

                        @foreach ($navbarMenus as $group => $menus)
                            @if (is_string($menus))
                                {{-- Untuk link tanpa submenu --}}
                                <li class="menu-item active">
                                    <a href="{{ url($menus) }}" class="menu-link">
                                        <i class="menu-icon tf-icons bx bx-folder"></i>
                                        <div class="text-truncate">{{ $group }}</div>
                                    </a>
                                </li>
                            @elseif ($menus->isNotEmpty())
                                {{-- Untuk menu dengan submenu --}}
                                <li class="menu-item active">
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="menu-icon tf-icons bx bx-folder"></i>
                                        <div class="text-truncate">{{ $group }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @foreach ($menus as $menu)
                                            <li class="menu-item active">
                                                <a href="{{ url($menu->nama_File ?? '#') }}" class="menu-link">
                                                    <i class="bi bi-person me-2"></i>{{ $menu->nama_Menu }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                </div>










            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-center me-3 d-none d-xl-block">
                        <a class="nav-item nav-link px-0" href="javascript:void(0);" id="desktop-toggle">
                            <i class="bx bx-chevron-left bx-md"></i>
                        </a>
                    </div>
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)" id="mobile-toggle">
                            <i class="bx bx-menu bx-md"></i>
                        </a>
                    </div>


                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">


                            <li class="nav-item navbar-dropdown dropdown-user dropdown">


                                <!-- Flex container to align name and avatar in the same row -->
                                <div class="d-flex align-items-center">
                                    <!-- User's name -->

                                    <span class="mt-5">
                                        <h3>{{ $navbarTitle ?? 'Default Title' }}</h3>
                                    </span>
                                </div>


                            </li>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Place this tag where you want the button to render. -->
                            {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}


                            <!-- User -->
                            <!-- User -->
                            {{-- <li class="nav-item me-4">
                                <button id="dark-mode-toggle" class="custom-toggle">
                                    <i class="bi bi-moon-fill bulan " id="moon-icon"></i>
                                    <i class="bi bi-brightness-high-fill  matahari" id="sun-icon"
                                        style="display: none;"></i>
                                    <!-- Initially hide sun icon -->
                                </button>
                            </li> --}}

                            <li class="nav-item navbar-dropdown dropdown-user dropdown ">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <!-- Flex container to align name and avatar in the same row -->
                                    <div class="d-flex align-items-center">
                                        <!-- User's name -->
                                        {{-- <span class="me-2">test</span> --}}
                                        <div class="avatar-icon avatar avatar-online"> t
                                        </div>
                                        <!-- Avatar image -->

                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            onclick="document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                                        </a>
                                    </li>

                                    <!-- Hidden logout form -->
                                    <form id="logout-form" method="POST" style="display: none;">
                                        @csrf
                                        @method('POST')
                                    </form>
                                </ul>
                            </li>
                            <!--/ User -->

                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">


                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body">
                                    Centrepark ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>

                                </div>

                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    {{-- DARK MODE --}}
    <script src="js/darkmode.js"></script>

    <script src="vendor/libs/popper/popper.js"></script>
    <script src="vendor/js/bootstrap.js"></script>

    <script src="vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="vendor/js/main.js"></script>

    <!-- Page JS -->



    <!-- Place this tag before closing body tag for github widget button. -->
</body>
<script>
    document.getElementById("desktop-toggle").addEventListener("click", function() {
        document.querySelector(".layout-wrapper").classList.toggle("layout-menu-collapsed");
    });
    document.getElementById("mobile-toggle").addEventListener("click", function() {
        document.querySelector(".layout-wrapper").classList.toggle("layout-menu-collapsed");
    });
</script>

</html>
