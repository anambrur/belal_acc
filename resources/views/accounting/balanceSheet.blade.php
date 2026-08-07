@extends('layout')
@section('title')
    Balance Sheet
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="row" style="width: 100%">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <h6>Customer Ledger List</h6>
                                <p class="m-0">Date: {{ date('d M Y') }}</p>
                                <p class="m-0">From Date:</p>
                                <p class="m-0">Thru Date:</p>
                            </div>
                            <div class="col-lg-5 col-sm-6 col-12">
                                <h5>{{ $store?->store_name ?? '—' }}</h5>
                                <h6>{{ $store?->owner_name ?? '' }}</h6>
                                <p class="m-0"><strong>Mobile:</strong>{{ $store?->phone ?? '—' }}</p>
                                <p class="m-0"><strong>Address:</strong>{{ $store?->full_address ?? '—' }}</p>
                            </div>
                            {{-- <div class="col-lg-4 col-sm-6 col-12">
                                <h6><strong>Customer Name :</strong>{{ $customer[0]->customer_name }} </h6>
                                <p class="m-0"><strong>Mobile :</strong>{{ $customer[0]->phone }} </p>
                                <p class="m-0"><strong>Address :</strong>{{ $customer[0]->location->address }} </p>
                            </div> --}}
                        </div>
                    </div>
                    <hr>
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
                    <hr>

                    <div class="table-responsive">
                        <table class="table datanew data-table table-striped" id="dataTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>
                                    <th>Debet</th>
                                    <th>Cradit</th>
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
        $(document).ready(function() {
            $("#dataTable").DataTable().destroy();
            var table = $(".data-table").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                pageLength: 10,
                lengthMenu: DtListHelpers.lengthMenu,
                language: {
                    lengthMenu: 'Show _MENU_ entries'
                },
                ajax: {
                    url: "{{ route('blanceSheetTable') }}",
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
                        data: "date",
                        name: "date"
                    },
                    {
                        data: "paid_amount",
                        name: "paid_amount"
                    },
                    {
                        data: "total_price",
                        name: "total_price"
                    },
                ],
                dom: DtListHelpers.dom,
                buttons: DtListHelpers.makeExportButtons({
                    columns: [0, 1, 2, 3, 4],
                    docTitle: 'BALANCE SHEET'
                })
            });


            // $(".select").select2();
            // $('#filter_company, #filter_category, #filter_batch').change(function() {
            //     table.draw();
            // });


        });
    </script>
@endpush


