<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = ['accounts_id', 'amount', 'date', 'payable_id', 'payable_type','payment_for'];
    function payable()
    {
        return $this->morphTo();
    }
    function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    function accounts()
    {
        return $this->belongsTo(Accounts::class);
    }
}
