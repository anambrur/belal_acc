<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'supplier_name', 'phone', 'road_id', 'location_id',
    ];

    function product()
    {
        return $this->hasMany(Product::class);
    }
    function purchase()
    {
        return $this->hasMany(Purchase::class);
    }
    function payment()
    {
        return $this->morphMany(Payment::class, 'payable');
    }
    function purchaseReturn()
    {
        return $this->hasMany(PurchaseReturn::class);
    }
    function road()
    {
        return $this->belongsTo(Road::class);
    }
    function location()
    {
        return $this->belongsTo(Location::class);
    }
    
}
