<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Product;
use Illuminate\Http\Request;
use SebastianBergmann\CodeCoverage\TestFixture\C;

class ProductCategoryController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credential')->only(['index']);
        $this->middleware('auth.api')->except(['index']);
    }

    public function index(Product $product){
        $categories = $product->categories;
        return $this->showAll($categories);
    }

    public function update(Product $product, Category $category){
        $product->categories()->syncWithoutDetaching([$category->id]);  // attach => create, sync => update(but all old value will be delete), syncWithoutDetaching => update(all old value will not delete and add new one but not duplicate)
        return $this->showAll($product->categories);
    }

    public function destroy(Product $product, Category $category){
        if (!$product->categories()->find($category->id))
        {
            return $this->errorResponse('The specify category is not the actual category of this product',422);
        }
        $product->categories()->detach([$category->id]);
        return  $this->showAll($product->categories);
    }

}
