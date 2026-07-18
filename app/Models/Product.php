<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name','bn_name', 'category_id', 'unit_id', 'price', 'company_id', 'batch_id'
    ];

    function category()
    {
        return $this->belongsTo(Category::class);
    }
    function batch()
    {
        return $this->belongsTo(Batch::class);
    }

    function unit()
    {
        return $this->belongsTo(Unit::class);
    }
    function company()
    {
        return $this->belongsTo(Company::class);
    }

    function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    function purchase()
    {
        return $this->hasMany(Purchase::class);
    }

    function sell()
    {
        return $this->hasMany(Sell::class);
    }
    function sellsReturn()
    {
        return $this->hasMany(SellsReturn::class);
    }
    function purchaseReturn()
    {
        return $this->hasMany(PurchaseReturn::class);
    }
}
