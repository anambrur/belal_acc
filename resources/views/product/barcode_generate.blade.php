@extends('layout')
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/barcode.css') }}" />
@endpush

@section('title')
    Barcode
@endsection

@section('content')

<style>
    
    @media print {
        .center-items {
            text-align: center !importnat;
        }
        
        .header, .page-title {
        display: none;
        }
        
        body {
        font-size: 12px;
        margin-top: -90px;
        }
    }

</style>
    <div class="page-wrapper">
        <div class="content">
            <div class="page-header">
                <div class="page-title">
                    <h4>Barcode Add</h4>
                    <h6>Create new Barcode</h6>
                </div>
                <div class="page-title">
                    <button class="btn btn-primary" id="codeAddbtn">Add</button>
                    <button class="btn btn-secondary ms-2" id="printButton">Print</button>
                </div>
            </div>
            <div class="row" id="codeContainer" style="display: flex;
                                    flex-wrap: wrap;">
                <div id="barcodeRow" class="d-none">
                    <div class="col-md-2 p-0 mb-2" style="text-align: center;   width: calc(100% / 6); /* 100% divided by 6 columns */;">
                        <p class="barcode-text m-0 p-0 fw-bolder barcode_text">{{ $product_name }}</p>
                        <svg id="barcode" jsbarcode-format="upc" jsbarcode-value="{{ $barcodeValue }}"
                            jsbarcode-textmargin="0" jsbarcode-fontoptions="bold">
                        </svg>
                        <p class="barcode-text m-0 fw-bolder">BC{{ $code }}</p>
                    </div>
                </div>
                <div class="col-md-2 p-0 mb-2 center-items" style="text-align: center;  width: calc(100% / 6); /* 100% divided by 6 columns */;">
                    <p class="barcode-text m-0 p-0 fw-bolder barcode_text">{{ $product_name }}</p>
                    <svg id="barcode" jsbarcode-format="upc" jsbarcode-value="{{ $barcodeValue }}" jsbarcode-textmargin="0"
                        jsbarcode-fontoptions="bold">
                    </svg>
                    <p class="barcode-text m-0 fw-bolder">BC{{ $code }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('assets/js/JsBarcode.all.min.js') }}"></script>
    
    <script>
        var barcodeValue = @json($barcodeValue);
        JsBarcode("#barcode", barcodeValue, {
            width: 0.8,
            height: 30,
            displayValue: false
        });
    </script>
    <script src="{{ asset('assets/js/custom_product.js') }}"></script>
@endpush
