@extends('layout')

@section('title')
    Stock Report
@endsection

@section('content')
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Stock Report</h4>
                    <h6>Manage your Inventory Report</h6>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table datanew">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Unit Price(৳)</th>
                                        <th>Current Stock(৳)</th>
                                        <th>Stock Value(Purchase৳)</th>
                                        <th>Stock Value(Sell৳)</th>
                                        <th>Potential Profit(৳)</th>
                                        <th>Total Unit Sell(৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($products as $d)
                                        {{-- {{ $d }} --}}
                                        <tr>
                                            <td class="productimgname">
                                                <a class="product-img">
                                                    <img src="{{ asset('storage/' .$d->photo) }}" alt="product" />
                                                </a>
                                                <p>{{ $d->product_name }}</p>
                                            </td>
                                            <td>{{ $d->category->category }}</td>
                                            <td>{{ $d->price }}</td>
                                            <td>{{ $d->current_stock }} {{ $d->unit->unit }}</td>
                                            <td>{{ number_format($d->price * $d->current_stock) }}</td>
                                            <td>{{ number_format($d->sell_price * $d->current_stock) }}</td>
                                            <td>{{ number_format($d->sell_price * $d->current_stock - $d->price * $d->current_stock) }}
                                            </td>
                                            <td>{{ $d->total_sales }}</td>

                                        </tr>
                                    @empty
                                    @endforelse

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="fs-6"> Total: {{ number_format($totalCurrentStockValue) }}
                                        </td>
                                        <td class="fs-6"> Total: {{ number_format($totalSellStockValue) }}
                                        </td>
                                        <td class="fs-6">৳ = {{ number_format($totalSellStockValue - $totalCurrentStockValue) }}</td>
                                        <td>1</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
