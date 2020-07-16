<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerProductController extends apiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer){
        $products = $buyer->transactions()->with('product')
        ->get()
        ->pluck('product')
        ->unique('id')
        ->values();

        return $this->showAll($products);
    }
}
