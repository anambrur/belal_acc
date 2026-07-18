<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accounts extends Model
{
    use HasFactory;

    protected $fillable = [
        'bank_name',
        'branch_name',
        'account_number',
        'date',
        'amount',
        'description',
        'status',
    ];
}
