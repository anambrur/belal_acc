<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable =['loan_holder','amount','date'];

    function loanPayment()
    {
        return $this->hasMany(LoanPayment::class);
    }
}
