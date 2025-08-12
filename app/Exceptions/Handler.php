<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Throwable;
use Exception;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        ExceptionLog::class
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
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

    public function report(Throwable $e): void
    {
        if ($e instanceof ExceptionLog) {
            switch ($e->type) {
                case 'emergency':
                    Log::emergency($e->getMessage(), $e->getContextData());
                    break;
                case 'error':
                    Log::error($e->getMessage(), $e->getContextData());
                    break;
            }
        }

        parent::report($e);
    }
}
