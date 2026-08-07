@extends('layout')

@section('title')
    Sells List
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
    <style>
        .sell-list-page .date-wrap {
            position: relative;
        }

        .sell-list-page .date-wrap .form-control {
            min-height: 42px;
            border-radius: 8px;
            padding-left: 2.4rem;
        }

        .sell-list-page .date-wrap .date-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #ff9f43;
            z-index: 2;
            pointer-events: none;
        }

        .sell-list-page .btn-reset-filters {
            min-height: 42px;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #e8ecf1;
            background: #fff;
            font-weight: 600;
        }

        .sell-list-page .select2-container .select2-selection--single {
            height: 42px;
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .sell-list-page .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            padding-left: 12px;
        }

        .sell-list-page .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }

        .daterangepicker {
            z-index: 1050;
        }
    </style>
@endpush

@section('content')
    <div class="page-wrapper sell-list-page">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Sales List</h4>
                    <h6>Manage your sales invoices</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('sell.create') }}" class="btn btn-added">
                        <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1" />Add Sales
                    </a>
                </div>
            </div>

            <div class="filter-panel">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3 col-md-6">
                        <label for="filter_customer" class="form-label">Customer</label>
                        <select class="select" id="filter_customer">
                            <option>All Customer</option>
                            @foreach ($customer as $d)
                                <option value="{{ $d->id }}">{{ $d->customer_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label for="filter_address" class="form-label">Address</label>
                        <select class="select" id="filter_address">
                            <option>All Address</option>
                            @foreach ($address as $d)
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
                        <h6>Sales invoices</h6>
                        <p class="hint mb-0 text-muted small">Sorted by date (newest first)</p>
                    </div>
                    <span class="badge bg-light text-dark border" id="active_range_label">Period: All time</span>
                </div>
                <div class="list-body">
                    <div class="table-responsive">
                        <table class="table data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Invoice</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Address</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Paid</th>
                                    <th class="text-end">Due</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"></th>
                                    <th>Page total</th>
                                    <th id="total-price-footer" class="text-end"></th>
                                    <th id="paid-amount-footer" class="text-end"></th>
                                    <th id="due-footer" class="text-end"></th>
                                    <th></th>
                                </tr>
                            </tfoot>
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

            if ($.fn.DataTable.isDataTable('#dataTable')) {
                $('#dataTable').DataTable().destroy();
            }

            var table = $('#dataTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                searchDelay: 400,
                pageLength: 10,
                order: [[4, 'desc']],
                language: {
                    search: '',
                    searchPlaceholder: 'Search customer, invoice, address…',
                    processing: 'Loading sales…'
                },
                ajax: {
                    url: "{{ route('getSellDataList') }}",
                    data: function(d) {
                        d.filter_customer = $('#filter_customer').val();
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
                        data: 'invoice_id',
                        name: 'invoice_id'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    },
                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        defaultContent: '—'
                    },
                    {
                        data: 'location_address',
                        name: 'location_address',
                        defaultContent: '—'
                    },
                    {
                        data: 'total_price',
                        name: 'total_price',
                        searchable: false,
                        className: 'text-end'
                    },
                    {
                        data: 'paid_amount',
                        name: 'paid_amount',
                        searchable: false,
                        className: 'text-end'
                    },
                    {
                        data: 'due_amount',
                        name: 'due_amount',
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                dom: "<'row align-items-center m-2'<'col-sm-12 col-md-5'B><'col-sm-12 col-md-7'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row align-items-center'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
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
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    }
                ],
                footerCallback: function() {
                    var api = this.api();
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ? i : 0;
                    };

                    var total = api.column(6, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var paid = api.column(7, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var due = api.column(8, {
                        page: 'current'
                    }).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    $(api.column(6).footer()).html(total.toFixed(2));
                    $(api.column(7).footer()).html(paid.toFixed(2));
                    $(api.column(8).footer()).html(due.toFixed(2));
                }
            });

            $('.select').select2({
                width: '100%'
            });

            $('#filter_customer, #filter_address').on('change', function() {
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
                $('#active_range_label').text('Period: ' + picker.startDate.format('DD MMM YYYY') +
                    ' — ' + picker.endDate.format('DD MMM YYYY'));
                table.draw();
            });

            $('#daterange').on('cancel.daterangepicker', function() {
                filter_date = '';
                $(this).val('');
                $('#active_range_label').text('Period: All time');
                table.draw();
            });

            $('#reset_filters').on('click', function() {
                filter_date = '';
                $('#daterange').val('');
                $('#filter_customer').val('All Customer').trigger('change.select2');
                $('#filter_address').val('All Address').trigger('change.select2');
                $('#active_range_label').text('Period: All time');
                table.search('').draw();
            });

            $(document).on('click', '.delete-button', function(e) {
                e.preventDefault();
                var deleteUrl = $(this).data('url');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Deleted!', response.message, 'success');
                                    table.ajax.reload();
                                } else {
                                    Swal.fire('Error!', response.message, 'error');
                                }
                            },
                            error: function() {
                                Swal.fire('Error!', 'Something went wrong. Please try again.',
                                    'error');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
