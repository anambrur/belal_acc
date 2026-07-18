@extends('layout')

@section('title')
    Product Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product Edit</h4>
                </div>
            </div>

            <form action="{{ route('product.update', $product) }}" method="post" enctype="multipart/form-data">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" name="product_name" value="{{ $product->product_name }}" />
                                    <div class="text-danger">
                                        @error('product_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Bangla Name</label>
                                    <input type="text" name="bn_name" value="{{ $product->bn_name }}" />
                                    <div class="text-danger">
                                        @error('bn_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Price</label>
                                    <input type="text" name="price" value="{{ $product->price }}" />

                                    <div class="text-danger">
                                        @error('price')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Company name</label>
                                    <select class="select" name="company_id" value="{{ old('company_id') }}">
                                        @forelse ($company as $d)
                                            <option value="{{ $d->id }}">{{ $d->company_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <div class="text-danger">
                                        @error('company_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="select" name="category_id">
                                        @forelse ($category as $d)
                                            <option value="{{ $d->id }}">{{ $d->category }}</option>
                                        @empty
                                        @endforelse

                                    </select>
                                    <div class="text-danger">
                                        @error('category_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select class="select" name="unit_id" value="{{ $d->unit }}">
                                        @forelse ($unit as $d)
                                            <option value="{{ $d->id }}">{{ $d->unit }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <div class="text-danger">
                                        @error('unit_id')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Batch</label>
                                    <select class="select" name="batch_name" value="{{ $d->batch_name }}">
                                        @forelse ($batch as $d)
                                            <option value="{{ $d->id }}">{{ $d->batch_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <div class="text-danger">
                                        @error('batch_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            
                            
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="productlist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
