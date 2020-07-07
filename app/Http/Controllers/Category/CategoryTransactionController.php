<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
    public function index(Category $category){
        $transactions = $category->products()
        ->whereHas('transactions') // whereHas() works basically the same as has() but allows you to specify additional filters for the related model to check.
        ->with('transactions')->get()
        ->pluck('transactions')
        ->collapse();
        return $this->showAll($transactions);
    }
}
