@extends('layout')

@section('title')
    Road Edit
@endsection



@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Road edit</h4>
                    <h6>Add/Update Road</h6>
                </div>
            </div>
            <form action="{{ route('road.update',$road) }}" method="post" id="jqvalidation">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label>Road Name</label>
                                    <input type="text" name="road"
                                        value="{{ $road->name }}">
                                    <div class="text-danger">
                                        @error('address')
                                            {{ $message }}
                                        @enderror
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


