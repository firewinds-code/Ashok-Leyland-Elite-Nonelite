<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
            //
        });
    }
    public function render($request, Throwable $exception)
     {
         $code = $exception->getCode();
         $message = $exception->getMessage();
       
         if ($code < 100 || $code >= 600) {
             //echo $code = \Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR;
         }
         //dd($message);
         if(!empty($message)){
            return response()->view('errors.syntax', ['message'=>$message], 422);
         }else{
            return response()->view('errors.404', [], 404);
         }
        
     }
    /* public function report(Throwable $e){
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->route('dashboard2');
        }
    } */
}
