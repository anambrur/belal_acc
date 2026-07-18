@extends('layout')

@section('title')
    Add Company
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Add Company</h4>
                    <h6>Create new product Company</h6>
                </div>
            </div>

            {{-- Category Submit Here --}}
            <form action="{{ route('company.store') }}" method="post">
                @csrf()
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Company Name</label>
                                    <input type="text" name="company" />
                                    <div class="text-danger">
                                        @error('company')
                                            {{ $messeage }}
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="categorylist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
