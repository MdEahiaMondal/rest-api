<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInput = [];
        foreach ($request->request->all() as $input => $value)
        {
            $transformedInput[$transformer::originalAttributes($input)] = $value;
        }
        $request->replace($transformedInput);
        $response =  $next($request);
        if (isset($response->exception) && $response->exception instanceof ValidationException) // if request has exception and this exception is validationException
        {
            $data = $response->getData(); // get all validation error
            $transformedError = []; // initialize for catch error
            foreach ($data->error as $filed => $error_message)
            {
                $transformedField = $transformer::transformedAttributes($filed); //get transform field name like (title from real field of name)
                $transformedError[$transformedField] = str_replace($filed, $transformedField, $error_message); // set new field and error with replace real field name with transform field
            }
            $data->error = $transformedError; // set all error
            $response->setData($data); // finaly set

        }
        return $response;
    }
}
