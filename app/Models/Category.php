<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\ValidationException;

class Category extends Model
{
    use HasFactory,SoftDeletes;

    /**
     * Get the expenses for the category.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
    
    public function checkIfExpenseExistForCategory($categoryId)
    {
        $expenseExist = Expense::where('category_id', $categoryId)->count();
        if ($expenseExist)
            throw ValidationException::withMessages(['category_name' => 'Please delete all expenses related to this category']);
    }
}
