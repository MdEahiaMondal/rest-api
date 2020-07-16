<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $buyers = Buyer::all();
        return $this->showAll($buyers);
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //

    }
}
