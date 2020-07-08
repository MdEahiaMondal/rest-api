<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait apiResponser
{

    private function successResponse($data, $code)
    {
        $data['code'] = $code;
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200){
        if ($collection->isEmpty()){
            return $this->successResponse(['data' => $collection], $code);
        }
        $transformer = $collection->first()->transformer;
        $collection = $this->makeDataTransformer($collection, $transformer);
       return $this->successResponse($collection, $code);
    }
    protected function showOne(Model $instance, $code = 200){
        $transformer = $instance->first()->transformer;
        $instance = $this->makeDataTransformer($instance, $transformer);
        return $this->successResponse($instance, $code);
    }

    protected function showMessages($message, $code = 200){
        return $this->successResponse($message, $code);
    }


    protected function makeDataTransformer($data, $transformer){
        $resource = fractal($data, new $transformer)->toArray();
        return $resource;
    }


}
