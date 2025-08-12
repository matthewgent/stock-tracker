<?php

namespace App\Exceptions;

class ErrorException extends ExceptionLog
{
    public function __construct(
        string $message = "",
        array $contextData = [],
    ) {
        parent::__construct('error', $message, $contextData);
    }
}
