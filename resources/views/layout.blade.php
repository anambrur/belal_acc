<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="POS - Bootstrap Admin Template" />
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive" />
    <meta name="author" content="Dreamguys - Bootstrap Admin Template" />
    <meta name="robots" content="noindex, nofollow" />

    <title>@yield('title')</title>

    @yield('csrf-token')

    <link rel="shortcut icon" type="image/x-icon" href="assets/img/logo-small.png" />

    <link rel="stylesheet" media='screen,print' href="{{ asset('assets/css/bootstrap.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />

    {{-- datatable --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/buttons.bootstrap4.min.css') }}" />


    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/error.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" />

    @push('css')
        <style>
            .dt-buttons {
                margin-top: 10px
            }

            .buttons-html5,
            .buttons-print {
                background: #ff9f43 !important;
                padding: 3px 5px;
                color: #fff;
                font-weight: 700;
                font-size: 14px;
                border: 1px solid #ff9f43;
            }

            .pagination {
                margin-bottom: 0;
            }

            .page-link {
                color: #ff9f43;
                background-color: #fff;
                border: 1px solid #dee2e6;
            }

            .page-item.active .page-link {
                color: #fff;
                background-color: #ff9f43;
                border-color: #ff9f43;
            }

            .page-link:hover {
                color: #e68a39;
                background-color: #e9ecef;
                border-color: #dee2e6;
            }

            .page-item.disabled .page-link {
                color: #6c757d;
                background-color: #fff;
                border-color: #dee2e6;
            }
        </style>
    @endpush


    @stack('css')



</head>

<body>
    {{-- <div id="global-loader">
        <div class="whirly-loader"></div>
    </div> --}}

    <div class="main-wrapper">

        @include('inc.header')

        @include('inc.sidebar')

        @yield('content')

    </div>

    <script src="{{ asset('assets/js/base_url.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.slimscroll.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- datatable js --}}
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/moment.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/dataTables.min.js') }}"></script> --}}


    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    @stack('js')
</body>

</html>
