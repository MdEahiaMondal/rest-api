<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BuyerController extends Controller
{

    public function index()
    {
        $buyers = Buyer::has('transactions')->get();
        return response()->json(['data' => $buyers], 200);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $buyer = Buyer::has('transactions')->findOrFail($id);
        return response()->json(['data' => $buyer], 200);
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
