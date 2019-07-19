<?php


namespace App\Exceptions\ApiExceptions;


use Throwable;

class ApiForbiddenException extends ApiException
{
    public function __construct($message = 'your action is forbidden', $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}