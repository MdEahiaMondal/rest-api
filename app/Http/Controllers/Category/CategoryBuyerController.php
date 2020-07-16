<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class CategoryBuyerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Category $category){
        $buyers = $category->products()
            ->has('transactions')
            ->with('transactions.buyer')->get()
        ->pluck('transactions') //[ [..], [...], [....] ] pluck(transactions.buyer) not work. it will work only for one collection like => [1,2,buyer=>[....]]
        ->collapse()
        ->pluck('buyer')
        ->unique('id')
        ->values();
        return $this->showAll($buyers);
    }
}
