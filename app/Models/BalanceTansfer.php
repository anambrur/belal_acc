<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceTansfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'reason',
        'amount',
        'date',
        'description',
        'status',
        'from_accounts_id',
        'to_accounts_id',
    ];
}
