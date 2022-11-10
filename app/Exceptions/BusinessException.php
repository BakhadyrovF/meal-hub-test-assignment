<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    /** @var string */
    private $customMessage;

    /** @var int */
    private $statusCode;

    public function __construct(string $customMessage, int $statusCode = 400)
    {
        $this->customMessage = $customMessage;
        $this->statusCode = $statusCode;
        parent::__construct('Business Exception');
    }

    public function getCustomMessage()
    {
        return $this->customMessage;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
