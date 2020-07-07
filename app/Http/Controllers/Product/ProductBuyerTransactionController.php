<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    public function store(Request $request, Product $product, $user_id){

        $this->validate($request, [
            'quantity' => 'required|integer|min:1'
        ]);

        $user = User::findOrFail($user_id);
        if ($user->id === $product->seller_id)
        {
            return $this->errorResponse('The buyer must be different from the seller', 409);
        }
        if (!$user->isVerified()){
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        if (!$product->seller->isVerified()){
            return $this->errorResponse('The seller must be a verified user', 409);
        }

        if (!$product->isAvailable()){
            return  $this->errorResponse('The product is not available', 409);
        }

        if ($request->quantity > $product->quantity)
        {
            return  $this->errorResponse('The product does not have enough units for this transactions', 409);
        }


        return  DB::transaction(function () use($request, $product, $user){ // here why use DB::transaction? because some time we need to update database value if incas happened any type of error that's time it will automatically roll back to old value
           $product->quantity  -= $request->quantity;
           $product->save();

           $transaction = Transaction::create([
               'product_id' => $product->id,
               'quantity' => $request->quantity,
               'buyer_id' => $user->id
           ]);
           return $this->showOne($transaction, 201);
        });
    }
}
