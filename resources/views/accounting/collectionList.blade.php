@extends('layout')

@section('title')
    Collection List
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
    <style>
        .collection-page {
            --c-accent: #ff9f43;
            --c-ink: #1f2937;
            --c-muted: #6b7280;
            --c-line: #e8ecf1;
            --c-soft: #f7f8fa;
            --c-credit: #059669;
        }

        .collection-page .page-header {
            margin-bottom: 1.25rem;
        }

        .collection-page .page-header .page-title h4 {
            margin-bottom: 0.15rem;
        }

        .collection-page .page-header .page-title h6 {
            color: var(--c-muted);
            font-weight: 400;
        }

        .collection-page .filter-panel {
            background: #fff;
            border: 1px solid var(--c-line);
            border-radius: 12px;
            padding: 1rem 1.15rem;
            margin-bottom: 1.25rem;
        }

        .collection-page .filter-panel .form-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: var(--c-muted);
            margin-bottom: 0.35rem;
        }

        .collection-page .filter-panel .form-control {
            min-height: 42px;
            border-radius: 8px;
            border-color: #dbe1ea;
        }

        .collection-page .filter-panel .form-control:focus {
            border-color: var(--c-accent);
            box-shadow: 0 0 0 0.2rem rgba(255, 159, 67, 0.2);
        }

        .collection-page .filter-panel .select2-container .select2-selection--single {
            height: 42px;
            border-radius: 8px;
            border-color: #dbe1ea;
            display: flex;
            align-items: center;
        }

        .collection-page .filter-panel .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 12px;
            color: var(--c-ink);
        }

        .collection-page .filter-panel .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .collection-page .date-wrap {
            position: relative;
        }

        .collection-page .date-wrap .form-control {
            padding-left: 2.4rem;
        }

        .collection-page .date-wrap .date-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--c-accent);
            pointer-events: none;
            z-index: 2;
        }

        .collection-page .btn-reset-filters {
            min-height: 42px;
            border-radius: 8px;
            border: 1px solid var(--c-line);
            background: #fff;
            color: var(--c-ink);
            font-weight: 600;
            width: 100%;
        }

        .collection-page .btn-reset-filters:hover {
            background: var(--c-soft);
            color: var(--c-ink);
        }

        .collection-page .list-card {
            background: #fff;
            border: 1px solid var(--c-line);
            border-radius: 12px;
            overflow: hidden;
        }

        .collection-page .list-toolbar {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.15rem;
            border-bottom: 1px solid var(--c-line);
            background: var(--c-soft);
        }

        .collection-page .list-toolbar h6 {
            margin: 0;
            font-weight: 700;
            color: var(--c-ink);
        }

        .collection-page .list-toolbar .hint {
            margin: 0.2rem 0 0;
            color: var(--c-muted);
            font-size: 0.8125rem;
        }

        .collection-page .range-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            background: #fff;
            border: 1px solid var(--c-line);
            color: var(--c-muted);
            font-size: 0.8125rem;
        }

        .collection-page .range-chip strong {
            color: var(--c-ink);
        }

        .collection-page .list-body {
            padding: 1rem 1.15rem 1.15rem;
        }

        .collection-page #dataTable {
            width: 100% !important;
            margin: 0;
            border-color: var(--c-line);
        }

        .collection-page #dataTable thead th {
            background: #f3f5f8;
            color: #374151;
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 700;
            border-color: var(--c-line);
            white-space: nowrap;
            padding: 0.85rem;
            vertical-align: middle;
        }

        .collection-page #dataTable tbody td {
            padding: 0.85rem;
            border-color: var(--c-line);
            vertical-align: middle;
            color: var(--c-ink);
            font-variant-numeric: tabular-nums;
        }

        .collection-page #dataTable tbody tr:hover {
            background: #fffaf5;
        }

        .collection-page .customer-cell {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            min-width: 150px;
        }

        .collection-page .customer-cell .avatar {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: #fff8f1;
            border: 1px solid #f3e4d4;
            color: var(--c-accent);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 700;
            flex-shrink: 0;
        }

        .collection-page .customer-cell .name {
            font-weight: 600;
            color: var(--c-ink);
        }

        .collection-page .amount-cell {
            color: var(--c-credit);
            font-weight: 700;
        }

        .collection-page .date-cell {
            font-weight: 600;
            white-space: nowrap;
        }

        .collection-page .dataTables_wrapper .dataTables_filter input {
            border-radius: 8px;
            border: 1px solid #dbe1ea;
            padding: 0.4rem 0.75rem;
            min-height: 38px;
        }

        .collection-page .dataTables_wrapper .dataTables_filter input:focus {
            border-color: var(--c-accent);
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(255, 159, 67, 0.2);
        }

        .collection-page .dataTables_wrapper .dataTables_info {
            color: var(--c-muted);
            font-size: 0.8125rem;
            padding-top: 0.85rem;
        }

        .collection-page .dataTables_wrapper .dataTables_paginate {
            padding-top: 0.5rem;
        }

        /* Keep daterangepicker above cards */
        .daterangepicker {
            z-index: 1050;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper collection-page">
        <div class="content">

            <div class="page-header">
                <div class="page-title">
                    <h4>Collections</h4>
                    <h6>Customer due-return payments</h6>
                </div>
            </div>

            {{-- Always-visible filters --}}
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

                    <div class="col-lg-4 col-md-8">
                        <label for="daterange" class="form-label">Date range</label>
                        <div class="date-wrap">
                            <i class="fa fa-calendar-alt date-icon"></i>
                            <input type="text" class="form-control" id="daterange" placeholder="Select date range"
                                autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <button type="button" class="btn btn-reset-filters" id="reset_filters">
                            <i class="fa fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="list-card">
                <div class="list-toolbar">
                    <div>
                        <h6>Collection entries</h6>
                        <p class="hint">Sorted by date (newest first)</p>
                    </div>
                    <span class="range-chip" id="active_range_chip">
                        <i class="fa fa-filter"></i>
                        Period: <strong id="active_range_label">All time</strong>
                    </span>
                </div>

                <div class="list-body">
                    <div class="table-responsive">
                        <table class="table data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Road</th>
                                    <th>Address</th>
                                    <th class="text-end">Amount</th>
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
    <script src="{{ asset('assets/plugins/daterangepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/js/daterangepicker.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var filter_date = '';

            function initials(name) {
                return String(name || 'C')
                    .trim()
                    .split(/\s+/)
                    .slice(0, 2)
                    .map(function(p) {
                        return p.charAt(0).toUpperCase();
                    })
                    .join('') || 'C';
            }

            function escapeHtml(text) {
                return $('<div>').text(text || '').html();
            }

            function setRangeLabel(text) {
                $('#active_range_label').text(text || 'All time');
            }

            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 400,
                pageLength: 25,
                lengthMenu: DtListHelpers.lengthMenu,
                order: [[1, 'desc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search customer, road, address…',
                    processing: 'Loading collections…',
                    emptyTable: 'No collection records found',
                    zeroRecords: 'No matching collections',
                    lengthMenu: 'Show _MENU_ entries'
                },
                ajax: {
                    url: "{{ route('collectionListData') }}",
                    data: function(d) {
                        d.filter_road = $('#filter_road').val();
                        d.filter_address = $('#filter_address').val();
                        d.date_range = filter_date;
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
                        data: 'date',
                        name: 'date',
                        render: function(data) {
                            return data
                                ? '<span class="date-cell">' + escapeHtml(data) + '</span>'
                                : '<span class="text-muted">—</span>';
                        }
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        defaultContent: '—',
                        render: function(data) {
                            if (!data) {
                                return '<span class="text-muted">—</span>';
                            }
                            return '<div class="customer-cell">' +
                                '<span class="avatar">' + initials(data) + '</span>' +
                                '<span class="name">' + escapeHtml(data) + '</span>' +
                                '</div>';
                        }
                    },
                    {
                        data: 'road_name',
                        name: 'road_name',
                        defaultContent: '—',
                        render: function(data) {
                            return data ? escapeHtml(data) : '<span class="text-muted">—</span>';
                        }
                    },
                    {
                        data: 'location_address',
                        name: 'location_address',
                        defaultContent: '—',
                        render: function(data) {
                            return data ? escapeHtml(data) : '<span class="text-muted">—</span>';
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        searchable: false,
                        className: 'text-end',
                        render: function(data) {
                            return '<span class="amount-cell">৳ ' + escapeHtml(data) + '</span>';
                        }
                    }
                ],
                dom: DtListHelpers.dom,
                buttons: DtListHelpers.makeExportButtons({
                    columns: [0, 1, 2, 3, 4, 5],
                    docTitle: 'COLLECTION LIST',
                    getFilterSummary: function() {
                        var parts = [];
                        var road = $('#filter_road').val();
                        var address = $('#filter_address').val();
                        if (road && road !== 'All Road' && road !== '') {
                            parts.push($('#filter_road option:selected').text().trim());
                        }
                        if (address && address !== 'All Address' && address !== '') {
                            parts.push($('#filter_address option:selected').text().trim());
                        }
                        if (typeof filter_date !== 'undefined' && filter_date) {
                            parts.push(filter_date);
                        }
                        return parts.join(' · ');
                    }
                })
            });

            $('.select').select2({
                width: '100%'
            });

            $('#filter_road, #filter_address').on('change', function() {
                table.draw();
            });

            $('#daterange').daterangepicker({
                opens: 'left',
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD',
                    cancelLabel: 'Clear'
                }
            });

            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                filter_date = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                    'YYYY-MM-DD');
                $(this).val(filter_date);
                setRangeLabel(picker.startDate.format('DD MMM YYYY') + ' — ' + picker.endDate.format(
                    'DD MMM YYYY'));
                table.draw();
            });

            $('#daterange').on('cancel.daterangepicker', function() {
                filter_date = '';
                $(this).val('');
                setRangeLabel('All time');
                table.draw();
            });

            $('#reset_filters').on('click', function() {
                filter_date = '';
                $('#daterange').val('');
                $('#filter_road').val('All Road').trigger('change.select2');
                $('#filter_address').val('All Address').trigger('change.select2');
                setRangeLabel('All time');
                table.search('').draw();
            });
        });
    </script>
@endpush
