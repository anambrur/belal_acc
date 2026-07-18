@extends('layout')

@section('title')
    Unit Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Product Edit Unit</h4>
                    <h6>Create new product Unit</h6>
                </div>
            </div>

            {{-- Unit Submit Here --}}
            <form action="{{ route('unit.update',$unit->id) }}" method="post">
                @csrf()
                @method('PUT')
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Unit Name</label>
                                    <input type="text" name="unit" value="{{$unit->unit}}"/>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
