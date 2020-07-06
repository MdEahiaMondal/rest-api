<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerCategoryController extends apiController
{
    public function index(Buyer $buyer){
        $categories = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
        ->collapse() //The collapse method can do single array [ [1,2,3], [4,5,6] ] => [1,2,3,4,5,6]
        ->unique('id')
        ->values(); // it can do remove empty categories when repeated
        return $this->showAll($categories);
    }
}
