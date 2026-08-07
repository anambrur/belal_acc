@extends('layout')

@section('title')
    Store Settings
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Store Settings</h4>
                    <h6>Update store / business profile</h6>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('store.update') }}" method="post" enctype="multipart/form-data" id="jqvalidation">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Store Name <span class="text-danger">*</span></label>
                                    <input type="text" name="store_name" class="form-control"
                                        value="{{ old('store_name', $store->store_name) }}"
                                        placeholder="Enter store name">
                                    <div class="text-danger">
                                        @error('store_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Owner Name <span class="text-danger">*</span></label>
                                    <input type="text" name="owner_name" class="form-control"
                                        value="{{ old('owner_name', $store->owner_name) }}"
                                        placeholder="Enter owner name">
                                    <div class="text-danger">
                                        @error('owner_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Phone <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ old('phone', $store->phone) }}"
                                        placeholder="Enter phone number">
                                    <div class="text-danger">
                                        @error('phone')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control"
                                        value="{{ old('email', $store->email) }}"
                                        placeholder="Enter email">
                                    <div class="text-danger">
                                        @error('email')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-sm-12">
                                <div class="form-group">
                                    <label>Address <span class="text-danger">*</span></label>
                                    <input type="text" name="address" class="form-control"
                                        value="{{ old('address', $store->address) }}"
                                        placeholder="Enter address">
                                    <div class="text-danger">
                                        @error('address')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" class="form-control"
                                        value="{{ old('city', $store->city) }}"
                                        placeholder="Enter city">
                                    <div class="text-danger">
                                        @error('city')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <div class="form-group">
                                    <label>Logo</label>
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <div class="text-danger">
                                        @error('logo')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                    @if ($store->exists)
                                        <div class="mt-2">
                                            <img src="{{ $store->logo_url }}" alt="{{ $store->store_name }}"
                                                style="max-height: 80px; max-width: 160px; object-fit: contain;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Invoice / PDF Footer Note</label>
                                    <textarea name="invoice_footer" class="form-control" rows="3"
                                        placeholder="Optional note shown on ledger PDF footer">{{ old('invoice_footer', $store->invoice_footer) }}</textarea>
                                    <div class="text-danger">
                                        @error('invoice_footer')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <button type="submit" class="btn btn-submit me-2">Save</button>
                                <a href="{{ route('dashboard.index') }}" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
