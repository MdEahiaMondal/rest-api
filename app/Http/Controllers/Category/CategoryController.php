<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Transformers\CategoryTransformer;
use Illuminate\Http\Request;

class CategoryController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credential')->only(['index', 'show']);
        $this->middleware('auth:api')->except(['index', 'show']);
        $this->middleware('transform.input:'.CategoryTransformer::class)->only(['store', 'update']);
    }


    public function index()
    {
        $categories = Category::all();
        return $this->showAll($categories);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
           'name' => 'required',
           'description' => 'required'
        ]);
        $category = Category::create($request->all());
        return $this->showOne($category, 201);
    }


    public function show(Category $category)
    {
        return $this->showOne($category);
    }


    public function update(Request $request, Category $category)
    {
        $category->fill($request->only(['name', 'description'])); // only this two filed update in the reason we use fill

        if ($category->isClean()){ // if old value is not clean it will return true
            return $this->errorResponse('You need to specify a different value to update', 422);
        }
        $category->save();
        return  $this->showOne($category);
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return $this->showOne($category);
    }
}
