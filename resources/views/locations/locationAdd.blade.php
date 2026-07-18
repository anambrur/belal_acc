@extends('layout')

@section('title')
    Location Add
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Location Add</h4>
                    <h6>Add/Update Location</h6>
                </div>
            </div>
            <form action="{{ route('location.store') }}" method="post" id="jqvalidation">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12">
                                <div class="form-group">
                                    <label>Location </label>
                                    <input type="text" name="address" value="" placeholder="Enter a location">
                                    <div class="text-danger">
                                        @error('address')
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


