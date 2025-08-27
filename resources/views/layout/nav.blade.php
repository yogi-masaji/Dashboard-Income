<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="vendor/" data-template="vertical-menu-template-free" data-style="light">

<head>
    @include('layout.head')
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>

<body class="preloader-active">
    {{-- Preloader Start --}}
    <div id="preloader">
        <div class="preloader-container text-center">
            <!-- SVG Logo based on the provided SVG code -->
            <!-- The viewBox is adjusted to frame the logo correctly based on its path data -->
            <svg class="logo-svg" width="150" height="90" viewBox="340 320 735 440" xmlns="http://www.w3.org/2000/svg">
                <!-- Blue Part (Outer) - Drawn first -->
                <path class="logo-path path-blue-outer" style="z-index: 1;" d="M681.31,358.13c.66-.29,1.52-.41,1.94-.9,22.55-26.5,52-32.49,85.38-32,74.47,1.09,149,.31,223.44.35,52.13,0,82.13,30.11,82.23,82.46q.26,136,.36,272c0,41.36-33.3,75.31-74.61,75.43q-125.74.36-251.51,0c-23.58,0-43.23-9.33-59.42-26.27-2.45-2.57-4.18-6-7.88-7.15-12.36-19.42-14.08-41-13.16-63.21.61-14.73.35-29.51,0-44.26-.27-13.45-6-18.1-19.27-17.55-8.34.35-14.06,5.16-19.48,10.35-24.79,23.7-54.79,30.8-86.87,22.78-37.86-9.47-61.49-35.42-68.5-73.93-7.21-39.63,7.27-72.08,40.48-94.06,32.17-21.31,66.16-21.15,99.56-.85,8.9,5.41,15.23,14.13,24.67,18.81,5.65,2.81,11.42,7.63,17.77,3.94,7.48-4.33,12.52-10.53,11.94-20.59-.81-14,.38-28.08-.34-42.08C666.93,399.05,669.37,377.66,681.31,358.13ZM785,541.2h-.21c0,40.27.11,80.54-.11,120.81,0,5.67.51,8.23,7.38,7.92,15.43-.7,30.92-.54,46.37-.13,7.15.19,9.23-2.21,9.11-9.35-.42-25.16.1-50.34-.34-75.5-.13-7.16,1.87-9.36,9.05-9.24,21.21.36,42.44.39,63.64-.13,37.34-.91,65-25.12,72.92-58.51,11.54-48.94-16.18-104.24-78-105-40.26-.48-80.54-.24-120.8-.62-6.77-.07-9.27,1.31-9.18,8.93C785.22,460.66,785,500.93,785,541.2Z" stroke="#024b80" stroke-width="5"/>
                <!-- White 'P' -->
                <path class="logo-path path-p" style="z-index: 2;" d="M785,541.2c0-40.27.24-80.54-.22-120.81-.09-7.62,2.41-9,9.18-8.93,40.26.38,80.54.14,120.8.62,61.87.75,89.59,56.05,78,105-7.87,33.39-35.58,57.6-72.92,58.51-21.2.52-42.43.49-63.64.13-7.18-.12-9.18,2.08-9.05,9.24.44,25.16-.08,50.34.34,75.5.12,7.14-2,9.54-9.11,9.35-15.45-.41-30.94-.57-46.37.13-6.87.31-7.41-2.25-7.38-7.92.22-40.27.11-80.54.11-120.81Zm96.51-75.48c-11.07,0-19.33.11-27.58,0-5.07-.09-6.83,2-6.74,7.12.28,16.15.39,32.32-.06,48.46-.17,6,2.2,7.52,7.49,7.42,8.61-.17,17.23-.08,25.85,0,12.53.11,24.88-.18,36.27-6.72,10.21-5.87,14.32-14.71,14-26-.32-10.13-2.08-18.24-12.56-24.88C905,462.78,891.74,467.41,881.49,465.72Z" stroke="#fdfdfd" stroke-width="5"/>
                <!-- Blue Part (Inner) -->
                <path class="logo-path path-blue-inner" style="z-index: 2;" d="M881.49,465.72c10.25,1.69,23.49-2.94,36.63,5.39,10.48,6.64,12.24,14.75,12.56,24.88.36,11.25-3.75,20.09-14,26-11.39,6.54-23.74,6.83-36.27,6.72-8.62-.07-17.24-.16-25.85,0-5.29.1-7.66-1.41-7.49-7.42.45-16.14.34-32.31.06-48.46-.09-5.15,1.67-7.21,6.74-7.12C862.16,465.83,870.42,465.72,881.49,465.72Z" stroke="#024b80" stroke-width="5"/>
                <!-- Yellow Part - Drawn last to be on top -->
                <path class="logo-path path-yellow" style="z-index: 3;" d="M681.31,358.13c-11.94,19.53-14.38,40.92-13.22,63.29.72,14-.47,28.1.34,42.08.58,10.06-4.46,16.26-11.94,20.59-6.35,3.69-12.12-1.13-17.77-3.94-9.44-4.68-15.77-13.4-24.67-18.81-33.4-20.3-67.39-20.46-99.56.85-33.21,22-47.69,54.43-40.48,94.06,7,38.51,30.64,64.46,68.5,73.93,32.08,8,62.08.92,86.87-22.78,5.42-5.19,11.14-10,19.48-10.35,13.32-.55,19,4.1,19.27,17.55.3,14.75.56,29.53,0,44.26-.92,22.26.8,43.79,13.16,63.21-38.65,26.27-81.75,36.78-127.86,33.87-91-5.73-165.38-63.82-194-150.27C320.21,487.3,395.91,355.25,517.48,329c53.63-11.59,104.19-4.49,152.35,21.3C673.93,352.47,678.27,354.37,681.31,358.13Z" stroke="#fbb902" stroke-width="5"/>
            </svg>
        </div>
    </div>
    {{-- Preloader End --}}

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
    <style>
        /* CSS Kustom untuk Dark Mode Toggle */
        .custom-dark-mode-toggle .form-check-input {
            width: 60px;
            height: 30px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            background-image: none !important;
            position: relative;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        /* Tombol bulat di dalam toggle dengan ikon SVG */
        .custom-dark-mode-toggle .form-check-input::after {
            content: '';
            position: absolute;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background-color: #2563eb;
            top: 3px;
            left: 4px;
            transition: transform 0.2s ease-in-out, background-color 0.2s ease-in-out;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3ccircle cx='12' cy='12' r='5'%3e%3c/circle%3e%3cline x1='12' y1='1' x2='12' y2='3'%3e%3c/line%3e%3cline x1='12' y1='21' x2='12' y2='23'%3e%3c/line%3e%3cline x1='4.22' y1='4.22' x2='5.64' y2='5.64'%3e%3c/line%3e%3cline x1='18.36' y1='18.36' x2='19.78' y2='19.78'%3e%3c/line%3e%3cline x1='1' y1='12' x2='3' y2='12'%3e%3c/line%3e%3cline x1='21' y1='12' x2='23' y2='12'%3e%3c/line%3e%3cline x1='4.22' y1='19.78' x2='5.64' y2='18.36'%3e%3c/line%3e%3cline x1='18.36' y1='5.64' x2='19.78' y2='4.22'%3e%3c/line%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: center;
        }

        /* Saat mode gelap aktif (checked) */
        .custom-dark-mode-toggle .form-check-input:checked {
            background-color: #212529;
            border-color: #444;
        }

        /* Pindahkan tombol ke kanan dan ganti ikon saat aktif */
        .custom-dark-mode-toggle .form-check-input:checked::after {
            transform: translateX(30px);
            background-color: #4a5568;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23fff' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpath d='M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z'%3e%3c/path%3e%3c/svg%3e");
        }

        /* Hapus efek focus glow */
        .custom-dark-mode-toggle .form-check-input:focus {
            box-shadow: none;
            border-color: #86b7fe;
        }
        a#mobile-toggle {
            color:#000;
        }
        .mode-gelap a#mobile-toggle {
            color: #fff !important;
        }
        .responsive-heading {
    font-size: clamp(14px, 2vw, 24px);
        line-height: 20px !important;
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

                    {{-- <a href="javascript:void(0);"
                        class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none" >
                        <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
                    </a> --}}
                </div>



                <div class="menu-inner-shadow"></div>
                <form method="POST" action="{{ route('set.location') }}">
                    @csrf
                    <div class="p-2">
                        <h5 class="brand-title text-center" style="color: #fff !important">
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

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <!-- Hamburger menu for mobile -->
    <div class="layout-menu-toggle navbar-nav align-items-center me-3 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" id="mobile-toggle">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <!-- Close button for mobile sidenav -->
    <div class="navbar-nav align-items-center d-xl-none ms-auto">
        <a class="nav-item nav-link px-0" href="javascript:void(0);" id="mobile-close-toggle" style="display: none;">
            <i class="bx bx-x bx-sm"></i>
        </a>
    </div>

    <!-- Desktop toggle -->
    <div class="layout-menu-toggle navbar-nav align-items-center me-3 d-none d-xl-block">
        <a class="nav-item nav-link px-0 text-dark" href="javascript:void(0);" id="desktop-toggle">
            <i class="bx bx-chevron-left bx-md"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Title -->
        <div class="navbar-nav align-items-center">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <div class="d-flex flex-column flex-md-row align-items-md-center">
    <h4 class="text-dark mb-1 responsive-heading">
        {{ $navbarTitle ?? 'Default Title' }}
    </h4>
    <small class="text-muted ms-md-3 d-md-none d-inline">
       {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y') }}
    </small>
</div>

            </li>
        </div>
        <!-- /Title -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item me-3 d-none d-md-block">
    {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, j F Y') }}
</li>
            <li class="nav-item me-3 d-flex align-items-center">
                <div class="form-check form-switch custom-dark-mode-toggle">
                    <input class="form-check-input" type="checkbox" id="darkModeToggle" role="switch">
                </div>
            </li>


            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
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
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();">
                                <i class="bx bx-power-off bx-md me-3"></i><span>Log Out</span>
                            </a>
                        </li>
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
    <script src="{{ asset('js/darkmode.js') }}"></script>

    <script src="vendor/libs/popper/popper.js"></script>
    <script src="vendor/js/bootstrap.js"></script>

    <script src="vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="vendor/js/main.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- Page JS -->




</body>
{{-- Preloader Script --}}
<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            const preloader = document.getElementById('preloader');
            preloader.classList.add('hidden');
            document.body.classList.remove('preloader-active');
        }, 5000); // 5000 milliseconds = 5 seconds to match animation
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileCloseToggle = document.getElementById('mobile-close-toggle');
    const layoutWrapper = document.querySelector('.layout-wrapper');
    const layoutMenu = document.getElementById('layout-menu');

    function toggleMobileNav() {
        layoutWrapper.classList.toggle('layout-menu-expanded'); // Use a different class for mobile

        // Toggle visibility of open/close icons
        if (layoutWrapper.classList.contains('layout-menu-expanded')) {
            mobileToggle.style.display = 'none';
            mobileCloseToggle.style.display = 'block';
        } else {
            mobileToggle.style.display = 'block';
            mobileCloseToggle.style.display = 'none';
        }
    }

    if (mobileToggle) {
        mobileToggle.addEventListener('click', toggleMobileNav);
    }

    if (mobileCloseToggle) {
        mobileCloseToggle.addEventListener('click', toggleMobileNav);
    }

    // Also, ensure the overlay closes the mobile nav
    const overlay = document.querySelector('.layout-overlay');
    if(overlay) {
        overlay.addEventListener('click', function() {
            if (window.innerWidth < 1200 && layoutWrapper.classList.contains('layout-menu-expanded')) {
                 toggleMobileNav();
            }
        });
    }
});
</script>

<!-- Add this CSS to your existing style section or a separate CSS file -->
<style>
    /* Styles for the expanded mobile menu */
    @media (max-width: 1199.98px) {
        .layout-wrapper.layout-menu-expanded .layout-menu {
            transform: translateX(0);
        }
        .layout-wrapper.layout-menu-expanded .layout-overlay {
            opacity: 1;
            visibility: visible;
        }

        /* Ensure the close button is positioned correctly */
        #mobile-close-toggle {
            position: absolute;
            right: 1.5rem; /* Adjust as needed */
            top: 50%;
            transform: translateY(-50%);
            z-index: 1050; /* Ensure it's above other elements */
        }
    }
</style>
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

<!-- Initialize Select2 -->
<script>
    $(document).ready(function() {
        // Initialize Select2 on the location select element
        $('#locationSelect').select2({
            theme: 'bootstrap-5',
            dropdownParent: $('#layout-menu')
        });

    });
</script>
<script>
    // Skrip ini hanya akan berjalan jika pengguna sudah diautentikasi
    @auth
        (function() {
            // Ambil durasi sesi dari konfigurasi Laravel (dalam milidetik)
            const sessionTimeout = {{ config('session.lifetime') * 60 * 1000 }};
            let logoutTimer;

            function resetTimer() {
                // Hapus timer sebelumnya
                clearTimeout(logoutTimer);

                // Atur timer baru
                logoutTimer = setTimeout(function() {
                    // Kirim form logout ketika timer berakhir
                    // Ini adalah cara yang benar karena logout Anda menggunakan metode POST
                    document.getElementById('logout-form').submit();
                }, sessionTimeout);
            }

            // Atur ulang timer saat halaman dimuat
            resetTimer();

            // Atur ulang timer jika ada aktivitas dari pengguna
            document.onmousemove = resetTimer;
            document.onkeypress = resetTimer;
            document.onclick = resetTimer;
            document.onscroll = resetTimer;
            document.ontouchstart = resetTimer;
        })();
    @endauth
</script>
</html>
