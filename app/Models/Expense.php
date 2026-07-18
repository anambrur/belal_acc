<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;
    protected $fillable = ['expense_category_id','date','amount','description'];
    function expenseCategory()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }

}
