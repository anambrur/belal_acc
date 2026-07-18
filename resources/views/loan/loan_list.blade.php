@extends('layout')

@section('title')
    Loan List
@endsection


@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Loan list</h4>
                    <h6>View/Search loan Unit</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('loan.create') }}" class="btn btn-added">
                        <img src="{{ asset('assets/img/icons/plus.svg') }}" class="me-1" alt="img" />Add Loan
                    </a>
                </div>
            </div>

            <hr>
            @if (Session::has('success'))
                <div class="alert alert-success">
                    {{ Session::get('success') }}
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

                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select">
                                            <option>Choose Unit</option>
                                            <option>Computers</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select">
                                            <option>Choose Sub Unit</option>
                                            <option>Fruits</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-6 col-12">
                                    <div class="form-group">
                                        <select class="select">
                                            <option>Choose Sub Brand</option>
                                            <option>Iphone</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-1 col-sm-6 col-12 ms-auto">
                                    <div class="form-group">
                                        <a class="btn btn-filters ms-auto"><img src="assets/img/icons/search-whites.svg"
                                                alt="img" /></a>
                                    </div>
                                </div>
                            </div>
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
                                    <th class="text-start">SL</th>
                                    <th>Loan Holder</th>
                                    <th class="text-start">Amount</th>
                                    <th class="text-start">Paid</th>
                                    <th class="text-start">Due</th>
                                    <th class="text-start">Date</th>
                                    <th>Status</th>
                                    <th>Payment</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($loans as $d)
                                    @php
                                        $paid_amount = $d->amount;
                                        $total_paid_amount = isset($d->loanPayment[0]) ? $d->loanPayment[0]->total_paid_amount : 0;
                                        $due = $paid_amount - $total_paid_amount;
                                    @endphp
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" />
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td class="text-start">{{ $loop->iteration }}</td>
                                        <td>{{ $d->loan_holder }}</td>
                                        <td class="text-start">{{ $d->amount }}</td>
                                        <td class="text-start">{{ $total_paid_amount }}</td>
                                        <td class="text-start" style="color: #FF0000">{{ $due }}</td>
                                        <td class="text-start">{{ $d->date }}</td>
                                        <td>
                                            @if ($due > 0)
                                                <a href="" class="btn btn-danger btn-sm"
                                                    style="color: #fff">Due</a>
                                            @else
                                                <a href="" class="btn btn-success btn-sm"
                                                    style="color: #fff">Paid</a>
                                            @endif

                                        </td>
                                        <td><a href="{{ route('loanPayment', $d->id) }}"
                                                class="btn btn-secondary btn-sm" style="color: #fff">Payment</a>
                                        </td>



                                        <td>
                                            <div class="d-flex align-items-center justify-content-center">
                                                <a href="{{ route('loan.edit', $d) }}">
                                                    <span><i class="fa-solid fa-pen "></i></span>
                                                </a>
                                                <form action="{{ route('loan.destroy', $d) }}" method="post">
                                                    @csrf()
                                                    @method('delete')
                                                    <button onclick="return confirm('Are you sure you want to delete?')"
                                                        class="btn"><i class="fas fa-trash"
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
