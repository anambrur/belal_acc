<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model
{
    use HasFactory;
    protected $fillable = [

        'invoice_id','supplier_id', 'product_id', 'quantity', 'purchase_price','total_amount', 'date'
    ];
    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
