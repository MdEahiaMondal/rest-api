<?php

namespace App\Exceptions;

use App\Traits\apiResponser;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use  apiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) // validation errors
        {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }
        if ($exception instanceof ModelNotFoundException) // model error(if search like user model but does not exist any result)
        {
            $model_name = strtolower(class_basename($exception->getModel()));
            return  $this->errorResponse("Does not exist {$model_name} with the specified identificator", 404);
        }
        if ($exception instanceof AuthenticationException) // who does not register user in our system
        {
            return  $this->unauthenticated($request, $exception);
        }
        if ($exception instanceof AuthorizationException) // who is register user but does not permission some system
        {
            return  $this->errorResponse($exception->getMessage(), 403);
        }
        if ($exception instanceof NotFoundHttpException){ // url wrong
            return  $this->errorResponse('The specified url can not be found', 404);
        }
        return parent::render($request, $exception);
    }


    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? $this->errorResponse('Unauthenticated', 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }
        return $request->expectsJson()
            ? $this->invalidJson($request, $e)
            : $this->invalid($request, $e);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return $this->errorResponse($exception->errors(), 422);
    }

}
