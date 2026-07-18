@extends('layout')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="row">
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget">
                        @php
                            $total_purchse_due = 0;
                            $total_purchse = 0;
                        @endphp

                        @forelse ($purchase as $pur)
                            @php
                                $total_purchse_due += $pur['purchase_due'];
                                $total_purchse += $pur['purchase'];
                            @endphp
                        @empty
                        @endforelse

                        <div class="dash-widgetimg">
                            <span><img src="assets/img/icons/dash1.svg" alt="img" /></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                ৳<span class="counters" data-count="{{ $total_purchse_due }}"></span>
                            </h5>
                            <h6>Total Purchase Due</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash1">
                        @php
                            $total_sell_due = 0;
                            $total_sell = 0;
                        @endphp
                        @forelse ($sell_data as $s)
                            @php
                                $total_sell_due += $s['sell_due'];
                                $total_sell += $s['sell'];
                            @endphp
                        @empty
                        @endforelse
                        <div class="dash-widgetimg">
                            <span><img src="assets/img/icons/dash2.svg" alt="img" /></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                ৳<span class="counters" data-count="{{ $total_sell_due }}"></span>
                            </h5>
                            <h6>Total Sales Due</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash2">
                        <div class="dash-widgetimg">
                            <span><img src="assets/img/icons/dash3.svg" alt="img" /></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                ৳<span class="counters" data-count="{{ $total_purchse }}"></span>
                            </h5>
                            <h6>Total Purchase</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12">
                    <div class="dash-widget dash3">
                        <div class="dash-widgetimg">
                            <span><img src="assets/img/icons/dash4.svg" alt="img" /></span>
                        </div>
                        <div class="dash-widgetcontent">
                            <h5>
                                ৳<span class="counters" data-count="{{ $total_sell }}"></span>
                            </h5>
                            <h6>Total Sale</h6>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count">
                        <div class="dash-counts">
                            <h4>{{ $customerCount }}</h4>
                            <h5>Customers</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="user"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das1">
                        <div class="dash-counts">
                            <h4>{{ $supplierCount }}</h4>
                            <h5>Suppliers</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="user-check"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das2">
                        <div class="dash-counts">
                            <h4>100</h4>
                            <h5>Purchase Invoice</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="file-text"></i>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-12 d-flex">
                    <div class="dash-count das3">
                        <div class="dash-counts">
                            <h4>105</h4>
                            <h5>Sales Invoice</h5>
                        </div>
                        <div class="dash-imgs">
                            <i data-feather="file"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Purchase & Sales</h5>
                            <div class="graph-sets">
                                <ul>
                                    <li>
                                        <span>Sales</span>
                                    </li>
                                    <li>
                                        <span>Purchase</span>
                                    </li>
                                </ul>
                                <div class="dropdown">
                                    <button class="btn btn-white btn-sm dropdown-toggle" type="button"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        2022
                                        <img src="assets/img/icons/dropdown.svg" alt="img" class="ms-2" />
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">2022</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">2021</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);" class="dropdown-item">2020</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="sales_chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 col-sm-12 col-12 d-flex">
                    <div class="card flex-fill">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">Recently Added Products</h4>
                            <div class="dropdown">
                                <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"
                                    class="dropset">
                                    <i class="fa fa-ellipsis-v"></i>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <li>
                                        <a href="productlist.html" class="dropdown-item">Product List</a>
                                    </li>
                                    <li>
                                        <a href="addproduct.html" class="dropdown-item">Product Add</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive dataview">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>Products</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="productimgname">
                                                    {{-- <a href="productlist.html" class="product-img">
                                                            <img src="{{ asset('storage/'.$d->photo) }}" alt="product" />
                                                        </a> --}}
                                                    <a href="">{{ $d->product_name }}</a>
                                                </td>
                                                <td>৳{{ $d->price }}</td>
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

        </div>
    </div>
@endsection
