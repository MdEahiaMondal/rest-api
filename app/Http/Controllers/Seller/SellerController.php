<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{

    public function index()
    {
        $sellers = Seller::all();
        return $this->showAll($sellers);
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Seller $seller)
    {
//        $seller = Seller::has('products')->findOrFail($id);
         return $this->showOne($seller);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
