<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Road extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    function customer()
    {
        return $this->hasMany(Customer::class);
    }
    function supplier()
    {
        return $this->hasMany(Supplier::class);
    }
}
