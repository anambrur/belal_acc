@extends('layout')
@section('title')
    Collection List
@endsection


@push('css')
    {{-- <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/bootstrap.min.css') }}" /> --}}
    <link rel="stylesheet" href="{{ asset('assets/plugins/daterangepicker/css/daterangepicker.css') }}" />
@endpush

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        {{-- <div class="row" style="width: 100%">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <h6>Customer Ledger List</h6>
                                <p class="m-0">Date: {{ date('d M Y') }}</p>
                                <p class="m-0">From Date:</p>
                                <p class="m-0">Thru Date:</p>
                            </div>
                            <div class="col-lg-5 col-sm-6 col-12">
                                <h5>Moon Trading</h5>
                                <h6>Belal Hosain</h6>
                                <p class="m-0"><strong>Mobile:</strong>01723074844 </p>
                                <p class="m-0"> <strong>Address:</strong>Central Road,Payrachottor,Rangpur</p>

                            </div>
                            
                        </div> --}}
                    </div>
                   
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
                    <div class="card mb-0" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg col-sm-6 col-12">
                                            <div class="form-group">
                                                <label>Select Road</label>
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
                                                <label>Select Address</label>
                                                <select class="select" id="filter_address">
                                                    <option>All Address</option>
                                                    @foreach ($locations as $d)
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
                                                <input type="text"  class="form-control" id="daterange">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew data-table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Road</th>
                                    <th>Address</th>
                                    <th>Amount</th>
                                    <th>Date</th>
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
    <script src="{{ asset('assets/plugins/daterangepicker/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/daterangepicker/js/daterangepicker.min.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            var filter_date = '';

            $("#dataTable").DataTable().destroy();
            var table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('collectionListData') }}",
                    data: function(d) {
                        d.filter_road = $('#filter_road').val();
                        d.filter_address = $('#filter_address').val();
                        d.date_range = filter_date;
                        

                    }
                },

                columns: [{
                        data: "sl",
                        name: "sl"
                    },
                    {
                        data: "payable.customer_name",
                        name: "payable.customer_name"
                    },
                    {
                        data: "payable.road.name",
                        name: "payable.road.name"
                    },
                    {
                        data: "payable.location.address",
                        name: "payable.location.address"
                    },
                    {
                        data: "amount",
                        name: "amount"
                    },
                    {
                        data: "date",
                        name: "date"
                    },
                ],
            });


            $(".select").select2();
            $('#filter_road, #filter_address').change(function() {
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

        });
    </script>
@endpush
