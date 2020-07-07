<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{

    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }


    public function store(Request $request, $user) // here why user. because we know if any user has a product thy are seller but when we store a product thats time allow all user.
    {
        $this->validate($request, [
           'name' => 'required',
           'description' => 'required',
           'quantity' => 'required|integer|min:1',
           'image' => 'required|image',
        ]);
        $data = $request->all();
        $data['status'] = Product::AVAILABLE_PRODUCT;
        $data['seller_id'] = $user;
        $data['image'] = '1.jpg';

        $product = Product::create($data);
        return $this->showOne($product, 201);
    }


    public function show(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        return $this->showOne($seller);
    }


    public function update(Request $request, Seller $seller, Product $product)
    {
        $this->validate($request, [
            'name' => 'nullable',
            'description' => 'nullable',
            'quantity' => 'nullable|integer|min:1',
            'image' => 'nullable|image',
            'status' => 'in:' . Product::AVAILABLE_PRODUCT . ',' . Product::UNAVAILABLE_PRODUCT
        ]);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        $this->checkSeller($seller, $product);

        if ($request->has('status')){
            if ($product->categories->count() === 0 && $product->isAvailable()){
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }

        if ($product->isClean())
        {
            return  $this->errorResponse('You need to specify a different value to update', 422);
        }

        $product->save();
        return $this->showOne($product);
    }


    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        $product->delete();
        return $this->showOne($product);
    }

    private function checkSeller(Seller $seller, Product $product)
    {
        if ($seller->id !== $product->seller_id)
        {
            throw new HttpException(403,'The specified seller is not the actual seller of this product');
        }
    }
}
