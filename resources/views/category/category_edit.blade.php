@extends('layout')

@section('title')
    Category Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product Edit Category</h4>
                </div>
            </div>

            {{-- Category Submit Here --}}
            <form action="{{ route('category.update', $category->id) }}" method="post">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Category Name</label>
                                    <input type="text" name="category" value="{{ $category->category }}" />
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="company" value="{{ $category->company }}" />
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <button class="btn btn-warning ">Update</button>
                                <a href="categorylist.html" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
