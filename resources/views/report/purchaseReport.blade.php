@extends('layout')

@section('title')
    Purchase List
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>PURCHASE LIST</h4>
                    <h6>Manage your purchases</h6>

                </div>
                <div class="page-btn">
                    <a href="{{ route('purchase.create') }}" class="btn btn-added">
                        <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" />Add New
                        Purchases
                    </a>
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

                    </div>



                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">

                            <div class="row">
                                <div class="col-lg col-sm-6 col-12">
                                    <div class="form-group">
                                        <label for="">Select Supplier</label>
                                        <select class="select" id="filter_supplier">
                                            <option>All Supplier</option>
                                            @foreach ($supplier as $d)
                                                <option value="{{ $d->id }}">{{ $d->supplier_name }}</option>
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
                                        <span class="input-group-text">📆</span>
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
                                    <th>SL</th>
                                    <th>Invoice Id</th>
                                    <th>Supplier Name</th>
                                    <th>Address</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Grand Total</th>
                                    <th>Paid</th>
                                    <th>Due</th>
                                    <th>Action</th>
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
                    url: "{{ route('getPurchaseDataList') }}",
                    data: function(d) {
                        d.filter_supplier = $('#filter_supplier').val();
                        d.filter_address = $('#filter_address').val();
                        d.date_range = filter_date;
                    }
                },
                columns: [{
                        data: "sl",
                        name: "sl"
                    },
                    {
                        data: "invoice_id",
                        name: "invoice_id "
                    },
                    {
                        data: "supplier.supplier_name",
                        name: "supplier.supplier_name"
                    },
                    {
                        data: "supplier.location.address",
                        name: "supplier.location.address"
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
                        name: "due "
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
            $('#filter_supplier, #filter_address').change(function() {
                table.draw();
            });
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
        });
    </script>
@endpush
