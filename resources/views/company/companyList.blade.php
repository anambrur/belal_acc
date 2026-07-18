@extends('layout')
@section('title')
    Category List
@endsection


@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product Company list</h4>
                    <h6>View/Search product Company</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('company.create') }}" class="btn btn-added">
                        <img src="{{asset('assets/img/icons/plus.svg')}}" class="me-1" alt="img" />Add Company
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
                                    <img src="{{asset('assets/img/icons/filter.svg')}}" alt="img" />
                                    <span><img src="{{asset('assets/img/icons/closes.svg')}}" alt="img" /></span>
                                </a>
                            </div>
                            <div class="search-input">
                                <a class="btn btn-searchset"><img src="{{asset('assets/img/icons/search-white.svg')}}"
                                        alt="img" /></a>
                            </div>
                        </div>
                        <div class="wordset">
                            <ul>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                            src="{{asset('assets/img/icons/pdf.svg')}}" alt="img" /></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                            src="{{asset('assets/img/icons/excel.svg')}}" alt="img" /></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                            src="{{asset('assets/img/icons/printer.svg')}}" alt="img" /></a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="card" id="filter_inputs">
                        <div class="card-body pb-0">
                            
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table datanew table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" id="select-all" />
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>SL</th>
                                    <th>Company name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($company as $d)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" />
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $d->company_name }}
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('company.edit', $d) }}">
                                                    <span><i class="fa-solid fa-pen "></i></span>
                                                </a>
                                                <form action="{{ route('company.destroy', $d) }}" method="post">
                                                    @csrf()
                                                    @method('delete')
                                                    <button onclick="return confirm('Are you sure you want to delete?')" class="btn"><i class="fas fa-trash"
                                                            style="color: #FF0000;"></i></button>

                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="5">Category not Found</td>
                                    </tr>
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
