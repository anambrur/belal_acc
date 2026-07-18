<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Company;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use DataTables;
use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $company = Company::orderBy('created_at', 'DESC')->get();
        $category = Category::orderBy('created_at', 'DESC')->get();
        $batch = Batch::orderBy('created_at', 'DESC')->get();
        return view('product/product_list', compact('company', 'category', 'batch'));
    }


    public function productListTable(Request $request)
    {
        $query = Product::with('category', 'unit', 'company', 'batch');

        if ($request->has('filter_company') && $request->filter_company != 'All Company') {
            $query->where('company_id', $request->filter_company);
        }

        if ($request->has('filter_category') && $request->filter_category != 'All Category') {
            $query->where('category_id', $request->filter_category);
        }

        if ($request->has('filter_batch') && $request->filter_batch != 'All Batch') {
            $query->where('batch_id', $request->filter_batch);
        }

        $data = $query->orderBy('created_at', 'DESC')->get();
        $sl = 0;

        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<div class="d-flex align-center ">
                        <a href="' . route('product.edit', $row->id) . '">
                            <span><i class="fa-solid fa-pen "></i></span>
                        </a>
                        
                        <form action="' . route('product.destroy', $row->id) . '" method="post">
                        <input type="hidden" name ="_token" value="' . csrf_token() . '"/>
                        <input type="hidden" name ="_method" value="delete"/>
                        <button onclick="return confirm(\'Are you sure you want to delete?\')" class="btn"><i class="fas fa-trash"
                        style="color: #FF0000;"></i></button>
                        </form>
                    </div>';

                return $btn;
            })
            ->addColumn('product_brand_logo', function ($row) {
                return '<img src="' . asset('storage/' . $row->photo) . '" border="0" width="40" class="img-rounded" align="center" />';
            })
            ->addColumn('sl', function () use (&$sl) {
                return ++$sl;
            })
            ->rawColumns(['product_brand_logo', 'action'])
            ->make(true);
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $barcodeNumber = uniqid();
        $category = Category::orderBy('created_at', 'DESC')->get();
        $unit = Unit::orderBy('created_at', 'DESC')->get();
        $company = Company::orderBy('created_at', 'DESC')->get();
        $batch = Batch::orderBy('created_at', 'DESC')->get();
        // dd($batch);

        return view('product/product_add', compact('category', 'unit', 'company', 'batch'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $request->validate(
            [
                'product_name' => 'required',
                'bn_name' => 'required',
                'price' => 'required',
                'category_id' => 'required',
                'unit_id' => 'required',
                'company_id' => 'required',
                'batch_id' => 'required',
            ]
        );

        $product_name = $request->product_name;
        $bn_name = $request->bn_name;
        $price = $request->price;
        $category_id = $request->category_id;
        $unit_id = $request->unit_id;
        $company_id = $request->company_id;
        $batch_id = $request->batch_id;

        // dd($company_id);
        Product::create(['product_name' => $product_name, 'bn_name' => $bn_name, 'price' => $price, 'category_id' => $category_id, 'unit_id' => $unit_id, 'company_id' => $company_id, 'batch_id' => $batch_id]);
        return redirect()->route('product.index')->with('success', 'Product added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // dd($product);
        $category = Category::orderBy('created_at', 'DESC')->get();
        $unit = Unit::orderBy('created_at', 'DESC')->get();
        $company = Company::orderBy('created_at', 'DESC')->get();
        $batch = Batch::orderBy('created_at', 'DESC')->get();

        return view('product/product_edit', compact('product', 'category', 'unit', 'company', 'batch'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {


        $request->validate(
            [
                'product_name' => 'required',
                'bn_name' => 'required',
                'price' => 'required',
                'company_id' => 'required',
                'category_id' => 'required',
                'unit_id' => 'required',
                'batch_name' => 'required',
            ]
        );
        // dd($request->all());
        $bn_name = $request->bn_name;
        $product_name = $request->product_name;
        $price = $request->price;
        $batch_name = $request->batch_name;
        $category_id = $request->category_id;
        $unit_id = $request->unit_id;
        $company_id = $request->company_id;

        $product->update(['product_name' => $product_name, 'bn_name' => $bn_name, 'price' => $price, 'category_id' => $category_id, 'unit_id' => $unit_id, 'company_id' => $company_id, 'batch_id' => $batch_name]);
        return redirect()->route('product.index')->with('success', 'Product update successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $photo = $product->photo;
        Storage::disk('public')->delete($photo);
        $product->delete();
        return redirect()->route('product.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function generateBarcode($id)
    {
        $product = Product::findOrFail($id);
        $barcodeValue = $product->barcode;
        $code = $product->code;
        $product_name = $product->product_name;
        return view('product/barcode_generate', compact('barcodeValue', 'code', 'product_name'));
    }
}
