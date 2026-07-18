<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReturn;
use App\Models\Sell;
use App\Models\SellsReturn;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all products
        $products = Product::all();
        // Initialize total current stock value
        $totalCurrentStockValue = 0;
        $totalSellStockValue = 0;
        // Calculate current stock levels for each product
        foreach ($products as $product) {
            $product->current_stock = $this->calculateStock($product->id);
            $product->total_sales = $this->calculateTotalSales($product->id);
            $product->sell_price = $this->calculateSalesPrice($product->id);
            $product->current_stock_value = $product->current_stock * $product->price;
            $totalCurrentStockValue += $product->current_stock_value;
            $totalSellStockValue += $product->current_stock * $product->sell_price;
            
        }
        return view('stockReport/stockReport', compact('products', 'totalCurrentStockValue','totalSellStockValue'));
    }


    private function calculateStock($productId)
    {
        // Calculate total purchases
        $totalPurchases = Purchase::where('product_id', $productId)->sum('quantity');
        // Calculate total sales
        $totalSales = Sell::where('product_id', $productId)->sum('quantity');
        // Calculate Total sell Return
        $totalSalesReturn = SellsReturn::where('product_id', $productId)->sum('quantity');
        // Calculate Total Purchase Return
        $totalPurchaseReturn = PurchaseReturn::where('product_id', $productId)->sum('quantity');

        // Calculate current stock
        $currentStock = ($totalPurchases + $totalSalesReturn) - ($totalSales + $totalPurchaseReturn);

        // return $sell_price;
        return $currentStock;
    }
    private function calculateTotalSales($productId)
    {
        // Calculate total sales for the product
        $totalSales = Sell::where('product_id', $productId)->sum('quantity');

        return $totalSales;
    }

    private function calculateSalesPrice($productId)
    {
        // Calculate total sales for the product
        $sell_prices = Purchase::where('product_id', $productId)->pluck('sell_price');
        $sell_price = 0;
        foreach ($sell_prices as $d) {
            $sell_price = $d;
        }
        return $sell_price;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Stock $stock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Stock $stock)
    {
        //
    }
}
