@extends('layout')

@section('title')
    Sell List
@endsection

@push('css')
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/bootstrap.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Sales List</h4>
                    <h6>Manage your sales</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('sell.create') }}" class="btn btn-added"><img
                            src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1" />Add Sales</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-path">
                                <a class="btn btn-filter" id="filter_search">
                                    <img src="{{ asset('assets/img/icons/filter.svg') }}" alt="img" />
                                    <span><img src="{{ asset('assets/img/icons/closes.svg') }}" alt="img" /></span>
                                </a>
                            </div>
                            <div class="search-input">
                                <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                        alt="img" /></a>
                            </div>
                        </div>
                        <div class="wordset">
                            <ul>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                            src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img" /></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                            src="{{ asset('assets/img/icons/excel.svg') }}" alt="img" /></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                            src="{{ asset('assets/img/icons/printer.svg') }}" alt="img" /></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">

                            <div class="row">
                                <div class="col-lg col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="">Select Customer</label>
                                        <select class="select" id="filter_customer">
                                            <option>All Customer</option>
                                            @foreach ($customer as $d)
                                                <option value="{{ $d->id }}">{{ $d->customer_name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="">Select Address</label>
                                        <select class="select" id="filter_address">
                                            <option>All Address</option>
                                            @foreach ($address as $d)
                                                <option value="{{ $d->id }}">{{ $d->address }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg col-sm-6 col-12">
                                    <div class="form-group m-0">
                                        <label for="">Select Data Range</label>
                                    </div>
                                    <div class="input-group">
                                        <span class="input-group-text">ðŸ“†</span>
                                        <input type="text" class="form-control" id="daterange">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>
                                        SL
                                    </th>
                                    <th>Customer Name</th>
                                    <th>Address</th>
                                    <th>Invoice No</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="5"></th>
                                    <th>Total</th>
                                    <th id="total-price-footer"></th>
                                    <th id="paid-amount-footer"></th>
                                    <th id="due-footer"></th>
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
            $(".select").select2();

            $("#dataTable").DataTable().destroy();
            var table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('getSellDataList') }}",
                    data: function(d) {
                        d.filter_customer = $('#filter_customer').val();
                        d.filter_address = $('#filter_address').val();
                        d.date_range = filter_date;
                    }
                },
                columns: [{
                        data: "sl",
                        name: "sl "
                    },
                    {
                        data: "customer.customer_name",
                        name: "customer.customer_name "
                    },
                    {
                        data: "customer.location.address",
                        name: "customer.location.address "
                    },
                    {
                        data: "invoice_id",
                        name: "invoice_id"
                    },
                    {
                        data: "date",
                        name: "date"
                    },

                    {
                        data: "status",
                        name: "status",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "total_price",
                        name: "total_price"
                    },
                    {
                        data: "paid_amount",
                        name: "paid_amount"
                    },

                    {
                        data: "due",
                        name: "due",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
                dom: "<'row'<'col-sm-12 col-md-3'B><'col-sm-12 col-md-9'f>>" +
                     "<'row'<'col-sm-12'tr>>" +
                     "<'row'<'col-sm-12 col-md-3'i><'col-sm-12 col-md-9'p>>",
                buttons: [{
                        extend: 'copy',
                        text: 'Copy',
                        titleAttr: 'Copy'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        titleAttr: 'Excel'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        titleAttr: 'PDF'
                    },
                    {
                        extend: 'print',
                        text: 'Print',
                        titleAttr: 'Print',
                        customize: function(win) {
                            var body = $(win.document.body);
                            body.addClass('print-container');

                            var table = body.find('table').clone().addClass('print-table');

                            // Clear body and add flex columns
                            body.empty().append(
                                '<div class="print-column"></div><div class="print-column"></div>'
                            );

                            // Append table to first column
                            var column1 = body.find('.print-column').first();
                            column1.append(table);

                            // Optionally, add logic to handle table overflow if needed

                            // Set font size for printing
                            body.css('font-size', '10pt');
                        },
                        exportOptions: {
                            columns: [0, 2, 7] // Adjust as needed
                        }
                    }
                ],
                footerCallback: function(row, data, start, end, display) {
                    var api = this.api();

                    // Function to sum the values of a column
                    var intVal = function(i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 :
                            typeof i === 'number' ? i : 0;
                    };

                    // Calculate totals for Total Price, Paid Amount, and Due
                    var total = api.column(6).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var paid = api.column(7).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var due = api.column(8).data().reduce(function(a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);

                    // Update footer with totals
                    $(api.column(6).footer()).html(total.toFixed(2));
                    $(api.column(7).footer()).html(paid.toFixed(2));
                    $(api.column(8).footer()).html(due.toFixed(2));
                }
            });

            $('#filter_customer, #filter_address').change(function() {
                table.draw();
            });
            //daterangepicker
            $('#daterange').daterangepicker({
                opens: 'left',
                locale: {
                    format: 'YYYY-MM-DD'
                }
            }, function(start, end, label) {
                filter_date = (start.format('YYYY-MM-DD') + ' - ' + end
                    .format('YYYY-MM-DD'));
                table.draw();
            });
            
            // Handle delete button click
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
                                    Swal.fire(
                                        'Deleted!',
                                        response.message,
                                        'success'
                                    );
                                    // Refresh the datatable
                                    $('.data-table').DataTable().ajax.reload();
                                } else {
                                    Swal.fire(
                                        'Error!',
                                        response.message,
                                        'error'
                                    );
                                }
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'Something went wrong. Please try again.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
