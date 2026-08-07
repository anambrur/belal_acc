@extends('layout')

@section('title')
    Product List
@endsection

{{-- @push('css')
    <style>
        @media print {

            /* Ensure the table takes up the full width and retains structure */
            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            /* Adjust column widths to fit the content */
            table th,
            table td {
                padding: 5px;
                border: 1px solid #ddd;
                word-wrap: break-word;
            }

            /* Hide non-print elements */
            #dataTable_wrapper .dt-buttons,
            #dataTable_wrapper .dataTables_filter,
            #dataTable_wrapper .dataTables_length,
            #dataTable_wrapper .dataTables_info,
            #dataTable_wrapper .dataTables_paginate {
                display: none;
            }

            /* Ensure the table header repeats on each new page */
            table thead {
                display: table-header-group;
            }

            /* Flexbox layout for the print page */
            .print-container {
                display: flex;
                flex-wrap: wrap;
                width: 100%;
            }

            .print-column {
                width: 50%;
                box-sizing: border-box;
                padding: 10px;
            }

            .print-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }
        }
    </style>
@endpush --}}

@push('css')
    <style>
        @media print {

            /* Ensure the table takes up the full width and retains structure */
            table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }

            /* Adjust column widths to fit the content */
            table th,
            table td {
                padding: 5px;
                border: 1px solid #ddd;
                word-wrap: break-word;
            }

            /* Hide non-print elements */
            #dataTable_wrapper .dt-buttons,
            #dataTable_wrapper .dataTables_filter,
            #dataTable_wrapper .dataTables_length,
            #dataTable_wrapper .dataTables_info,
            #dataTable_wrapper .dataTables_paginate {
                display: none;
            }

            /* Ensure the table header repeats on each new page */
            table thead {
                display: table-header-group;
            }

            /* Flexbox layout for the print page */
            .print-container {
                display: flex;
                width: 100%;
            }

            .print-column {
                width: 50%;
                box-sizing: border-box;
                padding: 10px;
            }

            .print-table {
                width: 100%;
                border-collapse: collapse;
                table-layout: fixed;
            }
        }
    </style>
@endpush


@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product List</h4>
                    <h6>Manage your products</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('product.create') }}" class="btn btn-added"><img
                            src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1" />Add New Product</a>
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
                    <div class="table-top m-0">
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

                    <div class="card mb-0" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select" id="filter_company">
                                            <option>All Company</option>
                                            @foreach ($company as $d)
                                                <option value="{{ $d->id }}">{{ $d->company_name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select" id="filter_category">
                                            <option>All Category</option>
                                            @foreach ($category as $d)
                                                <option value="{{ $d->id }}">{{ $d->category }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select" id="filter_batch">
                                            <option>All Batch</option>
                                            @foreach ($batch as $d)
                                                <option value="{{ $d->id }}">{{ $d->batch_name }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                    <th>Product Name</th>
                                    <th>Name</th>
                                    <th>Company</th>
                                    <th>Category</th>
                                    <th>unit</< /th>
                                    <th>Batch</th>
                                    <th>Price</th>
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
                    url: "{{ route('productListTable') }}",
                    data: function(d) {
                        d.filter_company = $('#filter_company').val();
                        d.filter_category = $('#filter_category').val();
                        d.filter_batch = $('#filter_batch').val();
                    }
                },

                columns: [{
                        data: "sl",
                        name: "sl"
                    },
                    {
                        data: "product_name",
                        name: "product_name"
                    },
                    {
                        data: "bn_name",
                        name: "bn_name"
                    },
                    {
                        data: "company.company_name",
                        name: "company.company_name"
                    },
                    {
                        data: "category.category",
                        name: "category.category"
                    },
                    {
                        data: "unit.unit",
                        name: "unit.unit"
                    },
                    {
                        data: "batch.batch_name",
                        name: "batch.batch_name"
                    },
                    {
                        data: "price",
                        name: "price"
                    },
                    {
                        data: "action",
                        name: "action",
                        orderable: false,
                        searchable: false
                    },
                ],

                dom: DtListHelpers.dom,
                buttons: DtListHelpers.makeExportButtons({
                    columns: [0, 1, 2, 3, 4, 5, 6, 7],
                    docTitle: 'PRODUCT LIST',
                    getFilterSummary: function() {
                        var parts = [];
                        var company = $('#filter_company').val();
                        var category = $('#filter_category').val();
                        var batch = $('#filter_batch').val();
                        if (company && company !== 'All' && company !== '') {
                            parts.push($('#filter_company option:selected').text().trim());
                        }
                        if (category && category !== 'All' && category !== '') {
                            parts.push($('#filter_category option:selected').text().trim());
                        }
                        if (batch && batch !== 'All' && batch !== '') {
                            parts.push($('#filter_batch option:selected').text().trim());
                        }
                        return parts.join(' · ');
                    }
                })
            });

            $(".select").select2();
            $('#filter_company, #filter_category, #filter_batch').change(function() {
                table.draw();
            });
        });
    </script>
@endpush
