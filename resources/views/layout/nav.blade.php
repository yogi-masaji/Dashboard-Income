<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="vendor/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard Income</title>
    <link rel="stylesheet" href="css/main.css">
    {{-- @vite('resources/css/main.css') --}}

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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.4/js/responsive.dataTables.js"></script>
    <!-- JSZip for export functionality -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- pdfMake for exporting as PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

    <!-- DataTables HTML5 export and print buttons -->
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.2/js/buttons.print.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <link rel="stylesheet" href="vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="vendor/css/theme-default.css" class="template-customizer-theme-css" />
    {{-- <link rel="stylesheet" href="vendor/css/demo.css" /> --}}

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="vendor/js/helpers.js"></script>


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
            color: #000000;
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
    <style>
        /* Sidebar animation and transition styles */
        .layout-menu {
            transition: width 0.3s ease, transform 0.3s ease, opacity 0.2s ease;
            will-change: width, transform, opacity;
        }

        .menu-animation {
            animation: sidebarPulse 0.3s ease;
        }

        @keyframes sidebarPulse {
            0% {
                opacity: 0.8;
            }

            50% {
                opacity: 0.95;
            }

            100% {
                opacity: 1;
            }
        }

        /* Improved overlay styles */
        .layout-overlay {
            transition: opacity 0.3s ease, visibility 0.3s ease;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(2px);
            opacity: 0;
            visibility: hidden;
        }

        .layout-wrapper:not(.layout-menu-collapsed) .layout-overlay {
            opacity: 1;
            visibility: visible;
        }

        /* Toggle button animation */
        .layout-menu-toggle i {
            transition: transform 0.3s ease;
        }

        .layout-menu-collapsed .layout-menu-toggle i.bx-chevron-right {
            transform: rotate(0deg);
        }

        .layout-wrapper:not(.layout-menu-collapsed) .layout-menu-toggle i.bx-chevron-left {
            transform: rotate(0deg);
        }

        /* Improved mobile experience */
        @media (max-width: 1199.98px) {
            .layout-menu {
                box-shadow: 0 0 50px rgba(0, 0, 0, 0.1);
                transform: translateX(-100%);
            }

            .layout-wrapper:not(.layout-menu-collapsed) .layout-menu {
                transform: translateX(0);
            }
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
                            <img src="{{ asset('/storage/logo.png') }}" alt="logo" height="60">
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
                        <h5 class="brand-title" style="color: #fff !important">
                            {{ session('selected_location_name', 'GRAND INDONESIA') }}</h5>

                        <label for="locationSelect" class="select-label">Select Location</label>
                        <select class="form-select" name="location_id" id="locationSelect">
                            @foreach ($locations as $lokasi)
                                <option value="{{ $lokasi->id_Lokasi }}"
                                    {{ session('selected_location_id') == $lokasi->id_Lokasi ? 'selected' : '' }}>
                                    {{ $lokasi->nama_Lokasi }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-submit mt-2 w-100">Submit</button>
                    </div>
                </form>

                <div class="scrollbar-container"
                    style="max-height: calc(100vh - 100px); overflow-y: auto;overflow-x: hidden;" id="scrollbar">
                    <ul class="menu-inner py-1">
                        <!-- Dashboards -->

                        @foreach ($navbarMenus as $menuItem)
                            <li class="menu-item active">
                                @if ($menuItem['children']->isNotEmpty())
                                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                                        <i class="menu-icon {{ $menuItem['icon'] }}"></i>
                                        <div class="text-truncate">{{ $menuItem['name'] }}</div>
                                    </a>
                                    <ul class="menu-sub">
                                        @foreach ($menuItem['children'] as $submenu)
                                            <li class="menu-item active">
                                                <a href="{{ url($submenu->nama_File ?? '#') }}" class="menu-link">
                                                    {{ $submenu->nama_Menu }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <a href="{{ url($menuItem['url']) }}" class="menu-link">
                                        <i class="menu-icon {{ $menuItem['icon'] }}"></i>
                                        <div class="text-truncate">{{ $menuItem['name'] }}</div>
                                    </a>
                                @endif
                            </li>
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
                        <a class="nav-item nav-link px-0 text-dark" href="javascript:void(0);" id="desktop-toggle">
                            <i class="bx bx-chevron-left bx-md"></i>
                        </a>
                    </div>
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6 text-dark" href="javascript:void(0)"
                            id="mobile-toggle">
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
                                        <h3 class="text-dark">{{ $navbarTitle ?? 'Default Title' }}</h3>
                                    </span>
                                </div>


                            </li>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">

                            <li class="nav-item me-3">
                                {{ \Carbon\Carbon::now()->translatedFormat('l, j F Y') }}
                            </li>

                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-icon avatar avatar-online">

                                            @if (Session::has('nama_Staff') && !empty(session('nama_Staff')))
                                                {{ strtoupper(substr(session('nama_Staff'), 0, 1)) }}
                                            @else
                                                U
                                            @endif
                                        </div>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0);"
                                            onclick="document.getElementById('logout-form').submit();">
                                            <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                                        </a>
                                    </li>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                    </form>
                                </ul>
                            </li>
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




</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const layoutWrapper = document.querySelector(".layout-wrapper");
        const desktopToggle = document.getElementById("desktop-toggle");
        const mobileToggle = document.getElementById("mobile-toggle");
        const layoutMenu = document.getElementById("layout-menu");
        const layoutPage = document.querySelector(".layout-page");
        const overlay = document.querySelector(".layout-overlay");

        // Track if toggle was just clicked to prevent immediate closing
        let toggleJustClicked = false;

        // Function to toggle sidebar
        function toggleSidebar() {
            layoutWrapper.classList.toggle("layout-menu-collapsed");

            // Add animation class
            layoutMenu.classList.add("menu-animation");

            // Remove animation class after transition completes
            setTimeout(() => {
                layoutMenu.classList.remove("menu-animation");
            }, 300);

            // Save state to localStorage
            const isCollapsed = layoutWrapper.classList.contains("layout-menu-collapsed");
            localStorage.setItem("sidebarState", isCollapsed ? "collapsed" : "expanded");

            // Set flag to prevent immediate closing on mobile
            toggleJustClicked = true;
            setTimeout(() => {
                toggleJustClicked = false;
            }, 100);
        }

        // Desktop toggle
        desktopToggle.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event from bubbling up
            toggleSidebar();

            // Rotate icon based on sidebar state
            const icon = this.querySelector("i");
            if (layoutWrapper.classList.contains("layout-menu-collapsed")) {
                icon.classList.remove("bx-chevron-left");
                icon.classList.add("bx-chevron-right");
            } else {
                icon.classList.remove("bx-chevron-right");
                icon.classList.add("bx-chevron-left");
            }
        });

        // Mobile toggle
        mobileToggle.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation(); // Prevent event from bubbling up
            toggleSidebar();
        });

        // Close sidebar when clicking on overlay
        if (overlay) {
            overlay.addEventListener("click", function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (!layoutWrapper.classList.contains("layout-menu-collapsed")) {
                    toggleSidebar();
                }
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener("click", function(e) {
            const isMobile = window.innerWidth < 1200; // xl breakpoint

            // Don't close if toggle was just clicked or if clicking inside the menu
            if (toggleJustClicked || e.target.closest("#layout-menu") || e.target.closest(
                    "#mobile-toggle")) {
                return;
            }

            if (isMobile &&
                !layoutWrapper.classList.contains("layout-menu-collapsed")) {
                toggleSidebar();
            }
        });

        // Prevent clicks inside the sidebar from closing it
        if (layoutMenu) {
            layoutMenu.addEventListener("click", function(e) {
                e.stopPropagation(); // Stop propagation to prevent document click handler
            });
        }

        // Handle window resize - but don't auto-collapse on resize
        let resizeTimer;
        window.addEventListener("resize", function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                // Only adjust for dramatic size changes like orientation change
                const isMobile = window.innerWidth < 1200;
                const wasDesktop = window.innerWidth >= 1200;

                // We don't auto-collapse anymore on resize to prevent disrupting the user
            }, 250);
        });

        // Restore sidebar state from localStorage on page load
        const savedState = localStorage.getItem("sidebarState");
        const isMobile = window.innerWidth < 1200;

        // On mobile, always start collapsed regardless of saved state
        if (isMobile && !layoutWrapper.classList.contains("layout-menu-collapsed")) {
            layoutWrapper.classList.add("layout-menu-collapsed");
        }
        // On desktop, respect the saved state
        else if (!isMobile && savedState === "collapsed" && !layoutWrapper.classList.contains(
                "layout-menu-collapsed")) {
            toggleSidebar();
            // Update desktop toggle icon
            const icon = desktopToggle.querySelector("i");
            icon.classList.remove("bx-chevron-left");
            icon.classList.add("bx-chevron-right");
        }
    });
</script>

</html>
