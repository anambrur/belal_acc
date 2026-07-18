@extends('layout')

@section('title')
    Supplier List
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="card mb-1" id="filter_input">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
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
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew data-table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Supplier Name</th>
                                    <th>Phone</th>
                                    <th>Road</th>
                                    <th>Address</th>
                                    <th>Total Purchase(৳)</th>
                                    <th>Total Return(৳)</th>
                                    <th>Due(৳)</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

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
        $(function() {
            $("#dataTable").DataTable().destroy();

            var table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('supplierDataList') }}",
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
                        data: "supplier.supplier_name",
                        name: "supplier.supplier_name "
                    },

                    {
                        data: "supplier.phone",
                        name: "supplier.phone"
                    },
                    {
                        data: "supplier.road",
                        name: "supplier.road"
                    },
                    {
                        data: "supplier.location",
                        name: "supplier.location"
                    },
                    {
                        data: "purchase",
                        name: "purchase"
                    },
                    {
                        data: "payment",
                        name: "payment "
                    },
                    {
                        data: "due",
                        name: "due "
                    },

                    {
                        data: "status",
                        name: "status",
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: "actionPayment",
                        name: "actionPayment",
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
