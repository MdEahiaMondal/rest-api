<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Transaction;
use Illuminate\Http\Request;

class TransactionCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credential')->only(['index']);
    }
    public function index(Transaction $transaction)
    {
        return  $this->showAll($transaction->product->categories);
    }
}
