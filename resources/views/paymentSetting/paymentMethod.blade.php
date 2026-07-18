@extends('layout')
@section('title')
    Payment Method
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Payment Settings</h4>
                    <h6>Manage Payment Settings</h6>
                </div>
                <div class="page-btn">
                    <a class="btn btn-added" data-bs-toggle="modal" data-bs-target="#addpayment"><img
                            src="{{ asset('assets/img/icons/plus.svg') }}" alt="img" class="me-2" />Add Payment
                        Settings</a>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-top">
                        <div class="search-set">
                            <div class="search-input">
                                <a class="btn btn-searchset"><img src="{{ asset('assets/img/icons/search-white.svg') }}"
                                        alt="img" /></a>
                            </div>
                        </div>
                        <div class="wordset">
                            <ul>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="pdf"><img
                                            src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img" /></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="excel"><img
                                            src="{{ asset('assets/img/icons/excel.svg') }}" alt="img" /></a>
                                </li>
                                <li>
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" title="print"><img
                                            src="{{ asset('assets/img/icons/printer.svg') }}" alt="img" /></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table datanew">
                            <thead>
                                <tr>
                                    <th>
                                        <label class="checkboxs">
                                            <input type="checkbox" />
                                            <span class="checkmarks"></span>
                                        </label>
                                    </th>
                                    <th>Payment Type Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($paymentMethod as $k=> $p)
                                    <tr>
                                        <td>
                                            <label class="checkboxs">
                                                <input type="checkbox" />
                                                <span class="checkmarks"></span>
                                            </label>
                                        </td>
                                        <td>{{ $p->payment_method }}</td>

                                        <td class="text-end d-flex">
                                            <div class="d-flex align-center">
                                                {{-- <a class="me-3" href="{{ route('paymentMethod.edit', $p) }}"
                                                    data-bs-toggle="modal" data-bs-target="#editpayment">
                                                    <span><i onclick="getPayId({{ $p->id }})"
                                                            id="editbtn_{{ $k }}"
                                                            class="fa-solid fa-pen"></i></span>

                                                </a> --}}
                                                <form action="{{ route('paymentMethod.destroy', $p) }}" method="post">
                                                    @csrf()
                                                    @method('delete')
                                                    <button onclick="return confirm('Are you sure you want to delete?')"
                                                        class="btn"><i class="fas fa-trash"
                                                            style="color: #FF0000;"></i></button>

                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="addpayment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add payment type</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('paymentMethod.store') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Payment Name</label>
                                    <input type="text" name="payment_name" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-submit">Confirm</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    {{-- <div class="modal fade" id="editpayment" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit payment type</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('paymentMethod.update') }}" method="post" id="paymentMethodForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>Payment Name</label>
                                    <input type="hidden" name="payment_method_id" id="payment_method_id" value="{{ $n->id }}"/>
                                    <input type="text" name="payment_method_edit" id="payment_method"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-submit">Update</button>
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
@endsection


@push('js')
    <script></script>
    <script src="{{ asset('assets/js/custom_payment_method.js') }}"></script>
@endpush
