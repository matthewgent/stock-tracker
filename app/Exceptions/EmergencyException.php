<?php

namespace App\Exceptions;

class EmergencyException extends ExceptionLog
{
    public function __construct(
        string $message = "",
        array $contextData = [],
    ) {
        parent::__construct('emergency', $message, $contextData);
    }
}
