@extends('layout')

@section('title')
    Supplier Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Supplier Edit Management</h4>
                    <h6>Edit Supplier</h6>
                </div>
            </div>

            <form action="{{ route('supplier.update', $supplier) }}" method="post" enctype="multipart/form-data">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Supplier Name</label>
                                    <input type="text" name="supplier_name" value="{{ old('supplier_name', $supplier->supplier_name) }}" />
                                    <div class="text-danger">
                                        @error('supplier_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}" />
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
                                    <select class="select" name="road_id">
                                        <option value="">Choose Road</option>
                                        @foreach ($roads as $road)
                                            <option value="{{ $road->id }}" {{ old('road_id', $supplier->road_id) == $road->id ? 'selected' : '' }}>
                                                {{ $road->name }}
                                            </option>
                                        @endforeach
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
                                    <select class="select" name="location_id">
                                        <option value="">Choose Address</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}" {{ old('location_id', $supplier->location_id) == $location->id ? 'selected' : '' }}>
                                                {{ $location->address }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-danger">
                                        @error('location_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Update Supplier</button>
                                <a href="{{ route('supplier.index') }}" class="btn btn-cancel">Cancel</a>
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