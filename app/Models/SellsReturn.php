<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellsReturn extends Model
{
    use HasFactory;
    protected $fillable = [
       'invoice_id', 'customer_id', 'product_id', 'quantity', 'sell_price', 'date','total_amount'
    ];
    function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
