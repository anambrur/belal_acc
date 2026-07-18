<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = ['invoice_id', 'supplier_id', 'date', 'total_price', 'total_discount', 'paid_amount','location_id'];

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
