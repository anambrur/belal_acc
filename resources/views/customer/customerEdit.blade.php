@extends('layout')

@section('title')
    Customer Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Customer Edit Page</h4>
                    <h6>Add/Update Customer</h6>
                </div>
            </div>

            <form action="{{ route('customer.update', $customer) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" name="customer_name" value="{{ $customer->customer_name }}" />
                                    <div class="text-danger">
                                        @error('customer_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ $customer->phone }}" />
                                    <div class="text-danger">
                                        @error('phone')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>


                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Road</label>
                                    <select class="select" name="road_id" value="{{ old('road_id') }}">
                                        <option value="{{ $customer->road_id }}">{{ $customer->road->name }}</option>
                                        @forelse ($road as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <div class="text-danger">
                                        @error('road_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <select class="select" name="location_id" value="{{ old('location_id') }}">
                                        <option value="{{ $customer->location_id }}">{{ $customer->location->address }}
                                        </option>
                                        @forelse ($location as $d)
                                            <option value="{{ $d->id }}">{{ $d->address }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <div class="text-danger">
                                        @error('location_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="javascript:void(0);" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $('.select').select2();
        });
    </script>
@endpush
