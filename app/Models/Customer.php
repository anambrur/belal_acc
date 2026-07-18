<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_name', 'phone', 'total_sell_due', 'total_due_return', 'road_id', 'location_id'
    ];
    function sell()
    {
        return $this->hasMany(Sell::class);
    }
    function sellReturn()
    {
        return $this->hasMany(SellsReturn::class);
    }
    function payment()
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    function road()
    {
        return $this->belongsTo(Road::class);
    }
    function location()
    {
        return $this->belongsTo(Location::class);
    }

    // function ProductReturn()
    // {
    //     return $this->morphMany(ProductReturn::class, 'returnable');
    // }
    // public function sells()
    // {
    //     return $this->hasMany(Sell::class);
    // }
    // function getsellinvoice($query) {
    //     return $query->
    // }
}
