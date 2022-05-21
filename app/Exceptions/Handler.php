<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use App\MyConfig\Globals;
use App\Traits\Api\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // dd('excepcion',$e);
            //
        });
    }

    /**
     * Prepare exception for rendering.
     *
     * @param  \Throwable  $e
     * @return \Throwable
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if($e->getCode() === Globals::EXCEPCION_CODE['login']){
            return Inertia::render('Auth/Login', ['status' => null, 'errors'=> [$e->getMessage()], 'canResetPassword' => true])
                ->toResponse($request)
                ->setStatusCode(200)
            ;
            
        }

        if($request->wantsJson()){

            // Endpoint no encontrado
            if($e instanceof NotFoundHttpException){
                return $this->errorResponse(
                    404,
                    __('api.msgNotFoundHttpException')
                );
            }

            // Recurso no encontrado
            if($e instanceof ModelNotFoundException){
                return $this->errorResponse(404, __('api.msgModelNotFoundException'));
            }

            // Errores de validacion
            if($e instanceof ValidationException){
                return $this->errorResponse(
                    403,
                    $e->validator->errors(),
                );
            }
        }

        return $response;
    }

    
}
