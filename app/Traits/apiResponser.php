<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

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
        $collection = $this->filterData($collection, $transformer);
        $collection = $this->shortData($collection, $transformer);
        $collection = $this->paginate($collection);
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

    protected function filterData(Collection $collection, $transformer){
        foreach (request()->query() as $key => $value){
            $attribute = $transformer::originalAttributes($key);
            if ($attribute && $value)
            {
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

    protected function shortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by'))
        {
            $attribute = $transformer::originalAttributes(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    protected function paginate(Collection $collection)
    {
        Validator::validate(request()->all(), [
            'per_page' => 'integer|min:2|max:50'
        ]);

        $page = LengthAwarePaginator::resolveCurrentPage(); //get current page
        $per_page = 10;
        if (request()->has('per_page'))
        {
            $per_page = request()->per_page;
        }

        $results = $collection->slice(($page -1) * $per_page,$per_page);
        $paginated = new LengthAwarePaginator($results, $collection->count(), $per_page, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }

    protected function makeDataTransformer($data, $transformer){
        $resource = fractal($data, new $transformer)->toArray();
        return $resource;
    }


}
