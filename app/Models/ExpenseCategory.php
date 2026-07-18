<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'expense_category'
    ];
    function expense()
    {
        return $this->hasMany(Expense::class);
    }
}
