<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_id', 'customer_id', 'total_price', 'paid_amount', 'total_discount', 'date', 'location_id'
    ];
    function product()
    {
        return $this->belongsTo(Product::class);
    }
    function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
