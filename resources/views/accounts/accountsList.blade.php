@extends('layout')

@section('title')
    Accounts List
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Accounts</h4>
                    <h6>Manage your Accounts</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('accounts.create') }}" class="btn btn-added"><img
                            src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1" />Add Accounts</a>
                </div>
            </div>

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
            @if (Session::has('delete'))
                <div class="alert alert-success">
                    {{ Session::get('delete') }}
                </div>
                <script>
                    setTimeout(function() {
                        $('.alert-success').fadeOut('slow');
                    }, 2000);
                </script>
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
                        <table class="table table-striped" id="">
                            @php
                                $netAmount = getAccountsBalance();
                                // dd($netAmount);
                            @endphp
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Bank Name</th>
                                    <th>Branch Name</th>
                                    <th>Account No</th>
                                    <th>Abailable Balance</th>
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accountsData as $accounts)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $accounts->bank_name }}
                                        </td>
                                        <td>
                                            {{ $accounts->branch_name }}
                                        </td>
                                        <td>
                                            {{ $accounts->account_number }}
                                        </td>
                                        <td>
                                            {{ $accounts->amount }}
                                        </td>
                                        <td>
                                            {{ $accounts->date }}
                                        </td>

                                        <td>
                                            {{ $accounts->description }}
                                        </td>

                                        <td>
                                            <span class="badge {{ $accounts->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                                {{ $accounts->status == 1 ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>


                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('accounts.edit', $accounts) }}">
                                                    <span><i class="fa-solid fa-pen "></i></span>
                                                </a>
                                                <form action="{{ route('accounts.destroy', $accounts) }}" method="post">
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
                                    <tr>
                                        <td class="text-center" colspan="9">Category not Found</td>
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
