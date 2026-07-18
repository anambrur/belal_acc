@extends('layout')
@section('title')
    Add Expense Category
@endsection
@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Expense Add</h4>
                    <h6>Add/Update Expenses</h6>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('expenseCategory.store') }}" method="post">
                        @csrf()
                        <div class="row">
                            <div class="col-lg-12 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Expense Category Name</label>
                                    <div class="input-groupicon">
                                        <input type="text" name="expense_category" value="{{old('expense_category')}}"/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button class="btn btn-submit me-2">Submit</button>
                                <a href="expenselist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
