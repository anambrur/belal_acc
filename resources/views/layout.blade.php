<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0" />
    <meta name="description" content="{{ $store?->store_name ?? 'POS' }} — Accounting" />
    <meta name="keywords"
        content="admin, estimates, bootstrap, business, corporate, creative, management, minimal, modern,  html5, responsive" />
    <meta name="author" content="{{ $store?->store_name ?? 'Store' }}" />
    <meta name="robots" content="noindex, nofollow" />

    <title>@yield('title'){{ $store ? ' — ' . $store->store_name : '' }}</title>

    @yield('csrf-token')

    <link rel="shortcut icon" type="image/x-icon"
        href="{{ $store?->logo_url ?? asset('assets/img/logo-small.png') }}" />

    <link rel="stylesheet" media='screen,print' href="{{ asset('assets/css/bootstrap.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/animate.css') }}" />

    {{-- datatable --}}
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap4.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatable/buttons.bootstrap4.min.css') }}" />


    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/modern-tables.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.datetimepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/error.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" />

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

    <script>
        window.appStore = {
            logo: @json($store?->logo_url ?? asset('assets/img/anamsLogo.png')),
            name: @json($store?->store_name ?? 'Store'),
            owner: @json($store?->owner_name ?? ''),
            phone: @json($store?->phone ?? ''),
            email: @json($store?->email ?? ''),
            address: @json($store?->full_address ?? '')
        };
    </script>
    <script src="{{ asset('assets/js/datatable-list-helpers.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

    <script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexchart/chart-data.js') }}"></script>

    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.datetimepicker.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

    @stack('js')
</body>

</html>
