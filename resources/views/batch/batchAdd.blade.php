@extends('layout')

@section('title')
    Batch Add
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Batch Add</h4>
                    <h6>Add/Update Batch</h6>
                </div>
            </div>
            <form action="{{ route('batch.store') }}" method="post" id="jqvalidation">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Batch Name</label>
                                    <input type="text" name="batch_name" value="" placeholder="Enter a Batch">
                                    <div class="text-danger">
                                        @error('batch_name')
                                            {{ $message }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12">
                                <hr>
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="purchaselist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
