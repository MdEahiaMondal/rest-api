<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerTransactionController extends apiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer){
        $transactions = $buyer->transactions;
        return $this->showAll($transactions);
    }
}
