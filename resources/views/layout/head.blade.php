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
    :root {
        --hue: 223;
        --bg: hsl(var(--hue), 90%, 90%);
        --fg: hsl(var(--hue), 90%, 10%);
        --primary: hsl(var(--hue), 90%, 50%);
        --trans-dur: 0.3s;
    }

    body.preloader-active {
        overflow: hidden;
    }

    #preloader {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #061933;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999999;
        transition: opacity 0.7s ease, visibility 0.7s ease;
    }

    #preloader.hidden {
        opacity: 0;
        visibility: hidden;
    }

    .pl2 {
        display: block;
        width: 8em;
        height: 8em;
    }

    .pl2__rect,
    .pl2__rect-g {
        animation: pl2-a 1.5s cubic-bezier(0.65, 0, 0.35, 1) infinite;
    }

    .pl2__rect {
        animation-name: pl2-b;
    }

    .pl2__rect-g .pl2__rect {
        transform-origin: 20px 128px;
    }

    .pl2__rect-g:first-child,
    .pl2__rect-g:first-child .pl2__rect {
        animation-delay: -0.25s;
    }

    .pl2__rect-g:nth-child(2),
    .pl2__rect-g:nth-child(2) .pl2__rect {
        animation-delay: -0.125s;
    }

    .pl2__rect-g:nth-child(2) .pl2__rect {
        transform-origin: 64px 128px;
    }

    .pl2__rect-g:nth-child(3) .pl2__rect {
        transform-origin: 108px 128px;
    }

    /* Dark theme */
    @media (prefers-color-scheme: dark) {
        :root {
            --bg: hsl(var(--hue), 90%, 10%);
            --fg: hsl(var(--hue), 90%, 90%);
        }
    }

    /* Animations */
    @keyframes pl2-a {

        from,
        25%,
        66.67%,
        to {
            transform: translateY(0);
        }

        50% {
            animation-timing-function: cubic-bezier(0.33, 0, 0.67, 0);
            transform: translateY(-80px);
        }
    }

    @keyframes pl2-b {

        from,
        to {
            animation-timing-function: cubic-bezier(0.33, 0, 0.67, 0);
            width: 40px;
            height: 24px;
            transform: rotate(180deg) translateX(0);
        }

        33.33% {
            animation-timing-function: cubic-bezier(0.33, 1, 0.67, 1);
            width: 20px;
            height: 64px;
            transform: rotate(180deg) translateX(10px);
        }

        66.67% {
            animation-timing-function: cubic-bezier(0.33, 1, 0.67, 1);
            width: 28px;
            height: 44px;
            transform: rotate(180deg) translateX(6px);
        }
    }
</style>

@stack('scripts')
