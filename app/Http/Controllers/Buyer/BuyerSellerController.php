<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerSellerController extends apiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller') // nested relationship
            ->get()
            ->pluck('product.seller')/*->pluck('seller');*/
            ->unique('id')
            ->values();
        return $this->showAll($sellers);
    }
}
