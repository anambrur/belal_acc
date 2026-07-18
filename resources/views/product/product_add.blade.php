@extends('layout')

@section('csrf-token')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('title')
    Add Product
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product Add</h4>
                    <h6>Create new product</h6>
                </div>
            </div>

            <form action="{{ route('product.store') }}" method="post" enctype="multipart/form-data" id="jqvalidation">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-lg-3 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Product Name</label>
                                    <input type="text" name="product_name" id="englishText"
                                        value="{{ old('product_name') }}"/>
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
                                    <input type="text" name="bn_name" id="banglaText" value="{{ old('bn_name') }}" />
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
                                    <input type="text" name="price" id="price" value="{{ old('price') }}"
                                        autocomplete="off" />
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
                                        <option value="">Choose Company</option>
                                        @forelse ($company as $d)
                                            <option value="{{ $d->id }}">{{ $d->company_name }}</option>
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
                                    <label>Category</label>
                                    <select class="select" name="category_id" value="{{ old('category_id') }}">
                                        <option value="">Choose Category</option>
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
                                    <select class="select" name="unit_id" value="{{ old('unit_id') }}">
                                        <option value="">Choose Unit</option>
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
                                    <select class="select" name="batch_id" value="{{ old('batch_id') }}">
                                        <option value="">Choose Batch</option>
                                        @forelse ($batch as $d)
                                            <option value="{{ $d->id }}">{{ $d->batch_name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    <div class="text-danger">
                                        @error('batch_id')
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
@push('js')
    <script src="{{ asset('assets/js/custom_product.js') }}"></script>
    <script src="{{ asset('assets/js/JsBarcode.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#englishText').on('keyup', function() {
                var englishText = $('#englishText').val();
                console.log(englishText)

                if (englishText.trim() !== "") {
                    $.ajax({
                        url: '{{ url('/transliterate') }}',
                        type: 'POST',
                        data: {
                            text: englishText,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            $('#banglaText').val(response.banglaText);

                        }
                    });
                } else {
                    $('#banglaText').val('');
                }
            });
        });
    </script>
@endpush
