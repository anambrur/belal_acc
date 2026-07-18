@extends('layout')

@section('title')
    Purchase Return list
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Purchase Return List</h4>
                    <h6>Manage your Returns</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('purchaseReturn.create') }}" class="btn btn-added"><img
                            src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-2" />Add New Purchase
                        Return</a>
                </div>
            </div>
            <hr>
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
                </div>
            @endif
            @if (Session::has('update'))
                <div class="alert alert-success">
                    {{ Session::get('update') }}
                </div>
            @endif
            @if (Session::has('delete'))
                <div class="alert alert-danger">
                    {{ Session::get('delete') }}
                </div>
            @endif
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



                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all" />
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Invoice Number</th>
                                    <th>Supplier Name</th>
                                    <th>Date</th>
                                    <th>Total Price</th>

                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchaseReturn as $d)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" />
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>{{ $d->invoice_id }}</td>
                                        <td>
                                            {{ $d->supplier->supplier_name }}
                                        </td>
                                        <td>{{ $d->date }}</td>
                                        <td>{{ $d->sum_of_total }}</td>

                                        <td>
                                            <div class="d-flex align-center">
                                                <a href="{{ route('purchaseReturn.show', $d->supplier_id) }}">
                                                    <span><i class="fa-solid fa-eye me-3"></i></span>
                                                </a>

                                                <form action="{{ route('purchaseReturn.destroy', $d) }}" method="post">
                                                    @csrf()
                                                    @method('delete')
                                                    <button class="btn"><i class="fas fa-trash"
                                                            style="color: #FF0000;"></i></button>

                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
