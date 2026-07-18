@extends('layout')
@section('title')
    Expense Edit
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Expense Edit</h4>
                    <h6>Add/Update Expenses</h6>
                </div>
            </div>
            <div class="card">
                <form action="{{route('expense.update',$expense)}}" method="post" id="jqvalidation">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Expense Category</label>
                                    <select class="select" name="expense_categories">
                                        <option value="{{$expense->expense_category_id }}">{{$expense->expenseCategory->expense_category}}</option>
                                        @forelse ($expense_categories as $d)
                                            <option value="{{$d->id}}">{{ $d->expense_category }}</option>
                                        @empty
                                        @endforelse

                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Expense Date </label>
                                    <div class="input-groupicon">
                                        <input type="text" placeholder="Choose Date" class="datetimepicker"
                                            name="date" id="datetimepicker" value="{{$expense->date}}"/>
                                        <div class="addonset">
                                            <img src="{{ asset('assets/img/icons/calendars.svg') }}" alt="img" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-12">
                                <div class="form-group">
                                    <label>Amount</label>
                                    <div class="input-groupicon">
                                        <input type="text" name="amount" value="{{$expense->amount}}" />
                                        <div class="addonset">৳</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea class="form-control" name="description" ></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <button href="javascript:void(0);" class="btn btn-submit me-2">Submit</button>
                                <a href="expenselist.html" class="btn btn-cancel">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('assets/js/custom_expense_add.js') }}"></script>
@endpush
