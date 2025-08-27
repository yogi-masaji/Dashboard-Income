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
<link rel="icon" type="image/x-icon" href="{{ asset('/storage/logo-cp-ico.png') }}" />

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />


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

{{-- CSS Preloader --}}
<style>
    body.preloader-active {
        overflow: hidden;
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999999;
        transition: opacity 0.7s ease, visibility 0.7s ease;
        /* Added a gradient background to make glassmorphism visible */
        background: linear-gradient(to right top, #0f2340, #0c1c36, #09162d, #061024, #061933, #1e1b4b, #312e81);
        background-size: 400% 400%;
        animation: gradientBG 15s ease infinite;
    }

    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    #preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .preloader-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        border-radius: 1rem;
        /* Glassmorphism styles */
        background-color: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        /* For Safari */
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
    }

    .logo-svg {
        /* New animation for a subtle pulse/glow effect */
        animation: pulse-glow 5s ease-in-out infinite;
    }

    .logo-path {
        stroke-linecap: round;
        stroke-linejoin: round;
        /* Start with no fill color */
        fill: transparent;
        /* A large dasharray value to cover the longest path */
        stroke-dasharray: 2500;
        stroke-dashoffset: 2500;
        /* Animation definition for drawing the line */
        animation-name: draw;
        animation-duration: 5s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }

    /* Keyframe animation for the line drawing effect */
    @keyframes draw {
        0% {
            stroke-dashoffset: 2500;
        }

        /* Draw the line over 40% of the animation time */
        40% {
            stroke-dashoffset: 0;
        }

        /* Hold the drawn state until 80% */
        80% {
            stroke-dashoffset: 0;
        }

        /* Undraw the line to loop */
        100% {
            stroke-dashoffset: -2500;
        }
    }

    /* Keyframes for filling the paths with color after they are drawn */
    @keyframes fill-yellow {

        0%,
        40% {
            fill: transparent;
        }

        /* Transparent while drawing */
        45%,
        80% {
            fill: #fbb902;
        }

        /* Fill with color (faster transition) */
        90%,
        100% {
            fill: transparent;
        }

        /* Back to transparent for the loop */
    }

    @keyframes fill-blue {

        0%,
        40% {
            fill: transparent;
        }

        45%,
        80% {
            fill: #024b80;
        }

        90%,
        100% {
            fill: transparent;
        }
    }

    @keyframes fill-white {

        0%,
        40% {
            fill: transparent;
        }

        45%,
        80% {
            fill: #fdfdfd;
        }

        90%,
        100% {
            fill: transparent;
        }
    }

    /* New keyframes for the pulse and glow effect on the whole SVG */
    @keyframes pulse-glow {

        0%,
        100% {
            transform: scale(1);
            filter: drop-shadow(0 0 2px rgba(255, 255, 255, 0));
        }

        50% {
            transform: scale(1.03);
            filter: drop-shadow(0 0 10px rgba(59, 130, 246, 0.3));
        }
    }

    /* Apply the fill animations */
    .path-yellow {
        animation-name: draw, fill-yellow;
    }

    .path-blue-outer,
    .path-blue-inner {
        animation-name: draw, fill-blue;
    }

    .path-p {
        animation-name: draw, fill-white;
    }

    /* Stagger the animation start for each part of the new logo */
    .path-blue-outer {
        animation-delay: 0s;
    }

    .path-p {
        animation-delay: 0.1s;
    }

    .path-blue-inner {
        animation-delay: 0.2s;
    }

    .path-yellow {
        animation-delay: 0.3s;
    }


    .loading-text {
        margin-top: 1.5rem;
        font-size: 1.125rem;
        /* text-lg */
        color: #e5e7eb;
        /* Tailwind gray-200 for better contrast */
        letter-spacing: 0.05em;
        animation: pulseText 5s ease-in-out infinite;
    }

    @keyframes pulseText {

        0%,
        100% {
            opacity: 0.5;
        }

        50% {
            opacity: 1;
        }
    }
</style>

@stack('scripts')
