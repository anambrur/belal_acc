@extends('layout')

@section('title')
    Customer List
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Customer List</h4>
                    <h6>Manage your Customers</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('customer.create') }}" class="btn btn-added">
                        <img src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" /> Add Customer
                    </a>
                </div>
            </div>

            <hr>
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

            <div class="card">
                <div class="card-body">
                    <div class="card mb-1" id="filter_input">
                        <div class="row">
                            <div class="col-lg col-sm-6 col-12">
                                <div class="form-group">
                                    <select class="select" id="filter_road">
                                        <option>All Road</option>
                                        @foreach ($roads as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg col-sm-6 col-12">
                                <div class="form-group">
                                    <select class="select" id="filter_address">
                                        <option>All Address</option>
                                        @foreach ($locations as $d)
                                            <option value="{{ $d->id }}">{{ $d->address }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Phone</th>
                                    <th>Road</th>
                                    <th>Address</th>
                                    <th>Total Sell(৳)</th>
                                    <th>Total Due Return(৳)</th>
                                    <th>Due(৳)</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be populated here -->
                            </tbody>
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
            $("#dataTable").DataTable().destroy();

            var table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('customerDataList') }}",
                    data: function(d) {
                        d.filter_road = $('#filter_road').val();
                        d.filter_address = $('#filter_address').val();
                    }
                },
                columns: [{
                        data: "sl",
                        name: "sl"
                    },
                    {
                        data: "customer.customer_name",
                        name: "customer.customer_name"
                    },
                    {
                        data: "customer.phone",
                        name: "customer.phone"
                    },
                    {
                        data: "customer.road",
                        name: "customer.road"
                    },
                    {
                        data: "customer.location",
                        name: "customer.location"
                    },
                    {
                        data: "sell",
                        name: "sell"
                    },
                    {
                        data: "payment",
                        name: "payment"
                    },
                    {
                        data: "due",
                        name: "due",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "status",
                        name: "status",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "actionPayment",
                        name: "actionPayment",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false
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
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7] // Specify the columns you want to print
                        }
                    }
                ]
            });

            $(".select").select2();
            $('#filter_road, #filter_address').change(function() {
                table.draw();
            });
        });
    </script>
@endpush
