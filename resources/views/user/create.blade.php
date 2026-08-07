@extends('layout')

@section('title')
    User Create
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>User Create</h4>
                    <h6>Manage your User</h6>
                </div>
                <div class="page-btn">
                    <a href="{{ route('user.create') }}" class="btn btn-added"><img
                            src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-1" />Add New User</a>
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
                    <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data" id="jqvalidation">
                        @csrf()
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>User Name</label>
                                            <input type="text" name="name" value="{{ old('name') }}" />
                                            <div class="text-danger">
                                                @error('name')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <input type="text" name="email" value="{{ old('email') }}" />
                                            <div class="text-danger">
                                                @error('email')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Phone</label>
                                            <input type="text" name="phone" value="{{ old('phone') }}" />
                                            <div class="text-danger">
                                                @error('phone')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input type="text" name="address" value="{{ old('address') }}" />
                                            <div class="text-danger">
                                                @error('address')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <input type="password" name="password" value="{{ old('password') }}" />
                                            <div class="text-danger">
                                                @error('password')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-4 col-12">
                                        <div class="form-group">
                                            <label>Photo / Logo</label>
                                            <input type="file" name="photo" accept="image/*" />
                                            <div class="text-danger">
                                                @error('photo')
                                                    {{ $message }}
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <button class="btn btn-submit me-2">Submit</button>
                                        <a href="{{ route('user.index') }}" class="btn btn-cancel">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
@endpush