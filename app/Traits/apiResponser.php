<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait apiResponser
{

    private function successResponse($data, $code)
    {
        return response()->json(['data' => $data, 'code' => $code], $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['message' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200){
       return $this->successResponse($collection, $code);
    }
    protected function showOne(Model $model, $code = 200){
        return $this->successResponse($model, $code);
    }
}
