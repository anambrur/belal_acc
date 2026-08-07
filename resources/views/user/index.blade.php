@extends('layout')

@section('title')
    Users List
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>User List</h4>
                    <h6>Manage your User</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('user.create') }}" class="btn btn-added"><img
                            src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1" />Add New
                        User</a>
                </div>
            </div>

            <hr>
            @if (Session::has('success'))
                <div id="success-alert" class="alert alert-success">
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




                    <div class="table-responsive">
                        <table class="table table-border table-hover table-striped datanew">

                            <thead>
                                <tr>
                                    <th class="text-start">SL</th>
                                    <th class="text-start">Photo</th>
                                    <th class="text-start">Name</th>
                                    <th class="text-start">Email</th>
                                    <th class="text-start">Phone</th>
                                    <th class="text-start">Address</th>
                                    <th class="text-start">Status</th>
                                    <th class="text-start">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $d)
                                    <tr>
                                        <td class="text-start">{{ $loop->iteration }}</td>
                                        <td class="text-start">
                                            <img src="{{ $d->photo_url }}" alt="{{ $d->name }}"
                                                width="40" height="40"
                                                style="object-fit: cover; border-radius: 50%;">
                                        </td>
                                        <td class="text-start">{{ $d->name }}</td>
                                        <td class="text-start">{{ $d->email }}</td>
                                        <td class="text-start">{{ $d->phone }}</td>
                                        <td class="text-start">{{ $d->address }}</td>
                                        
                                        <td class="text-start">
                                            <select class="select change_product_status"
                                                route="' . config('constant.smAdminSlug') . '/product_status_update' . '"
                                                post_id="' . $v_data->id . '">
                                                <option value="{{ $d->status }}" ' . $selected1 . '>{{ $d->status }}
                                                </option>
                                            </select>

                                        </td>
                                        <td class="text-start">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('user.edit', $d) }}">
                                                    <span><i class="fa-solid fa-pen "></i></span>
                                                </a>
                                                <form action="{{ route('user.destroy', $d) }}" method="post">
                                                    @csrf()
                                                    @method('delete')
                                                    <button onclick="return confirm('Are you sure you want to delete?')"
                                                        class="btn"><i class="fas fa-trash"
                                                            style="color: #FF0000;"></i></button>

                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush