@extends('layout')

@section('title')
    Supplier List
@endsection

@push('css')
    <style>
        .supplier-list-page .btn-reset-filters {
            min-height: 42px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #e8ecf1;
            background: #fff;
            font-weight: 600;
        }

        .supplier-list-page .btn-reset-filters:hover {
            background: #f7f8fa;
        }

        .supplier-list-page .select2-container .select2-selection--single {
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .supplier-list-page .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 12px;
        }

        .supplier-list-page .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .supplier-list-page .payment-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.7rem;
            border-radius: 8px;
            background: #fff8f1;
            border: 1px solid #f3e4d4;
            color: #c2410c;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
        }

        .supplier-list-page .payment-link:hover {
            background: #ff9f43;
            border-color: #ff9f43;
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper supplier-list-page">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Supplier List</h4>
                    <h6>Manage suppliers, balances &amp; payments</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('supplier.create') }}" class="btn btn-added">
                        <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1" /> Add Supplier
                    </a>
                </div>
            </div>

            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        $('.alert-success').fadeOut('slow');
                    }, 2000);
                </script>
            @endif

            <div class="filter-panel">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="filter_road" class="form-label">Road</label>
                        <select class="select" id="filter_road">
                            <option>All Road</option>
                            @foreach ($roads as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="filter_address" class="form-label">Address</label>
                        <select class="select" id="filter_address">
                            <option>All Address</option>
                            @foreach ($locations as $d)
                                <option value="{{ $d->id }}">{{ $d->address }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="filter_status" class="form-label">Balance status</label>
                        <select class="select" id="filter_status">
                            <option>All Status</option>
                            <option value="Due">Due</option>
                            <option value="Paid">Paid</option>
                            <option value="Overpaid">Overpaid</option>
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <button type="button" class="btn btn-reset-filters" id="reset_filters">
                            <i class="fa fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="list-stats" id="filter_stats">
                <div class="list-stat-card is-invoices">
                    <div class="stat-top">
                        <div class="stat-label">Suppliers</div>
                        <span class="stat-icon"><i class="fa fa-truck"></i></span>
                    </div>
                    <div class="stat-value" id="stat_count">0</div>
                    <div class="stat-hint"><span id="stat_due_count">0</span> with outstanding due</div>
                </div>
                <div class="list-stat-card is-total">
                    <div class="stat-top">
                        <div class="stat-label">Total purchase</div>
                        <span class="stat-icon"><i class="fa fa-shopping-cart"></i></span>
                    </div>
                    <div class="stat-value" id="stat_purchase">৳0.00</div>
                    <div class="stat-hint">Purchases of filtered suppliers</div>
                </div>
                <div class="list-stat-card is-paid">
                    <div class="stat-top">
                        <div class="stat-label">Paid</div>
                        <span class="stat-icon"><i class="fa fa-check-circle"></i></span>
                    </div>
                    <div class="stat-value" id="stat_payment">৳0.00</div>
                    <div class="stat-hint">Payments made to suppliers</div>
                </div>
                <div class="list-stat-card is-due">
                    <div class="stat-top">
                        <div class="stat-label">Due</div>
                        <span class="stat-icon"><i class="fa fa-exclamation-circle"></i></span>
                    </div>
                    <div class="stat-value" id="stat_due">৳0.00</div>
                    <div class="stat-hint">Outstanding supplier balance</div>
                </div>
            </div>

            <div class="list-card">
                <div class="list-toolbar">
                    <div>
                        <h6>Suppliers</h6>
                        <p class="hint mb-0 text-muted small">Due = purchase − return − payment</p>
                    </div>
                    <span class="badge bg-light text-dark border" id="active_filter_label">Filters: All</span>
                </div>
                <div class="list-body">
                    <div class="table-responsive">
                        <table class="table data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Supplier</th>
                                    <th>Phone</th>
                                    <th>Road</th>
                                    <th>Address</th>
                                    <th class="text-end">Purchase</th>
                                    <th class="text-end">Payment</th>
                                    <th class="text-end">Due</th>
                                    <th>Status</th>
                                    <th class="text-center">Pay</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            function formatMoney(value) {
                var num = Number(value || 0);
                return '৳' + num.toLocaleString(undefined, {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            function updateFilterStats(stats) {
                stats = stats || {};
                $('#stat_count').text(Number(stats.total_count || 0).toLocaleString());
                $('#stat_due_count').text(Number(stats.due_count || 0).toLocaleString());
                $('#stat_purchase').text(formatMoney(stats.purchase_total));
                $('#stat_payment').text(formatMoney(stats.payment_total));
                $('#stat_due').text(formatMoney(stats.due_total));
            }

            function updateFilterLabel() {
                var road = $('#filter_road').val();
                var address = $('#filter_address').val();
                var status = $('#filter_status').val();
                var parts = [];

                if (road && road !== 'All Road') {
                    parts.push($('#filter_road option:selected').text().trim());
                }
                if (address && address !== 'All Address') {
                    parts.push($('#filter_address option:selected').text().trim());
                }
                if (status && status !== 'All Status') {
                    parts.push(status);
                }

                $('#active_filter_label').text(parts.length ? 'Filters: ' + parts.join(' · ') : 'Filters: All');
            }

            var storePrint = {
                logo: @json($store?->logo_url ?? asset('assets/img/anamsLogo.png')),
                name: @json($store?->store_name ?? 'Store'),
                owner: @json($store?->owner_name ?? ''),
                phone: @json($store?->phone ?? ''),
                email: @json($store?->email ?? ''),
                address: @json($store?->full_address ?? ''),
                docTitle: 'SUPPLIER LIST'
            };

            function buildActiveFilterSummary() {
                var parts = [];
                var road = $('#filter_road').val();
                var address = $('#filter_address').val();
                var status = $('#filter_status').val();

                if (road && road !== 'All Road') {
                    parts.push($('#filter_road option:selected').text().trim());
                }
                if (address && address !== 'All Address') {
                    parts.push($('#filter_address option:selected').text().trim());
                }
                if (status && status !== 'All Status') {
                    parts.push(status);
                }

                return parts.length ? parts.join(' · ') : '';
            }

            function customizeListPrint(win) {
                var doc = win.document;
                var $body = $(doc.body);
                var now = new Date();
                var generated = now.toLocaleString(undefined, {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                var filterSummary = buildActiveFilterSummary();
                var metaLines = [];

                if (storePrint.owner) {
                    metaLines.push(storePrint.owner);
                }
                if (storePrint.phone) {
                    metaLines.push('Mobile: ' + storePrint.phone);
                }
                if (storePrint.email) {
                    metaLines.push('Email: ' + storePrint.email);
                }
                if (storePrint.address) {
                    metaLines.push(storePrint.address);
                }

                $('<style>').text(
                    'body{font-family:DejaVu Sans,Arial,sans-serif;font-size:11px;color:#1a1a1a;margin:24px;}' +
                    'h1{display:none!important;}' +
                    '.print-invoice-header{width:100%;border-bottom:2px solid #1e3a5f;padding-bottom:14px;margin-bottom:14px;}' +
                    '.print-invoice-header table{width:100%;border-collapse:collapse;}' +
                    '.print-invoice-header td{vertical-align:top;}' +
                    '.print-logo{max-height:64px;max-width:140px;}' +
                    '.print-store-name{font-size:20px;font-weight:700;color:#1e3a5f;margin:0 0 4px;}' +
                    '.print-store-meta{font-size:10px;color:#444;line-height:1.55;margin:0;}' +
                    '.print-doc-row{display:flex;justify-content:space-between;align-items:center;margin:0 0 16px;gap:12px;}' +
                    '.print-doc-badge{display:inline-block;background:#1e3a5f;color:#fff;font-size:12px;font-weight:700;letter-spacing:1px;padding:8px 14px;}' +
                    '.print-doc-meta{text-align:right;font-size:10px;color:#555;line-height:1.6;}' +
                    '.print-filter-line{font-size:10px;color:#555;margin:0 0 12px;}' +
                    'table.dataTable{width:100%!important;border-collapse:collapse;}' +
                    'table.dataTable thead th{background:#eee!important;border-bottom:1px solid #ccc;padding:6px 8px;text-align:left;}' +
                    'table.dataTable tbody td{border-bottom:1px solid #e5e5e5;padding:6px 8px;}'
                ).appendTo(doc.head);

                var headerHtml =
                    '<div class="print-invoice-header">' +
                    '<table><tr>' +
                    '<td width="28%"><img class="print-logo" src="' + storePrint.logo + '" alt="' + storePrint.name + '"></td>' +
                    '<td width="72%" style="text-align:right;">' +
                    '<p class="print-store-name">' + storePrint.name + '</p>' +
                    '<p class="print-store-meta">' + metaLines.join('<br>') + '</p>' +
                    '</td></tr></table></div>' +
                    '<div class="print-doc-row">' +
                    '<span class="print-doc-badge">' + storePrint.docTitle + '</span>' +
                    '<span class="print-doc-meta">Generated: ' + generated + '</span>' +
                    '</div>';

                if (filterSummary) {
                    headerHtml += '<p class="print-filter-line">Filters: ' + filterSummary + '</p>';
                }

                $body.prepend(headerHtml);
            }

            function printAllFilteredRows(e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;
                var total = dt.page.info().recordsDisplay;

                dt.one('preXhr', function(e, s, data) {
                    data.start = 0;
                    data.length = total > 0 ? total : -1;
                });

                dt.one('draw', function() {
                    $.fn.dataTable.ext.buttons.print.action.call(self, e, dt, button, config);
                    dt.one('preXhr', function(e, s, data) {
                        data.start = oldStart;
                    });
                    setTimeout(function() {
                        dt.ajax.reload(null, false);
                    }, 0);
                });

                dt.ajax.reload();
            }

            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 400,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                order: [[1, 'asc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search name, phone, road, address…',
                    processing: 'Loading suppliers…',
                    lengthMenu: 'Show _MENU_ entries'
                },
                ajax: {
                    url: "{{ route('supplierDataList') }}",
                    data: function(d) {
                        d.filter_road = $('#filter_road').val();
                        d.filter_address = $('#filter_address').val();
                        d.filter_status = $('#filter_status').val();
                    },
                    dataSrc: function(json) {
                        updateFilterStats(json.stats);
                        return json.data;
                    }
                },
                columns: [{
                        data: null,
                        name: 'sl',
                        orderable: false,
                        searchable: false,
                        className: 'text-muted',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier_name',
                        defaultContent: '—'
                    },
                    {
                        data: 'phone',
                        name: 'phone',
                        defaultContent: '—'
                    },
                    {
                        data: 'road_name',
                        name: 'road_name',
                        defaultContent: '—'
                    },
                    {
                        data: 'location_address',
                        name: 'location_address',
                        defaultContent: '—'
                    },
                    {
                        data: 'purchase_total',
                        name: 'purchase_total',
                        searchable: false,
                        className: 'text-end'
                    },
                    {
                        data: 'payment_total',
                        name: 'payment_total',
                        searchable: false,
                        className: 'text-end'
                    },
                    {
                        data: 'due_total',
                        name: 'due_total',
                        searchable: false,
                        className: 'text-end'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actionPayment',
                        name: 'actionPayment',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'row align-items-center m-2'<'col-sm-12 col-md-5'B><'col-sm-12 col-md-7'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row align-items-center'<'col-sm-12 col-md-3'l><'col-sm-12 col-md-4'i><'col-sm-12 col-md-5'p>>",
                buttons: [{
                        extend: 'copy',
                        text: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        title: '',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        },
                        customize: customizeListPrint,
                        action: printAllFilteredRows
                    }
                ]
            });

            $('.select').select2({
                width: '100%'
            });

            $('#filter_road, #filter_address, #filter_status').on('change', function() {
                updateFilterLabel();
                table.draw();
            });

            $('#reset_filters').on('click', function() {
                $('#filter_road').val('All Road').trigger('change.select2');
                $('#filter_address').val('All Address').trigger('change.select2');
                $('#filter_status').val('All Status').trigger('change.select2');
                updateFilterLabel();
                table.search('').draw();
            });

            updateFilterLabel();
        });
    </script>
@endpush
