<?php

namespace App\Exceptions;

use Exception;

class ExceptionLog extends Exception
{
    public function __construct(
        public string $type,
        string $message = "",
        private array $contextData = []
    ) {
        parent::__construct($message);
    }

    public function getContextData(): array
    {
        return $this->contextData;
    }
}
