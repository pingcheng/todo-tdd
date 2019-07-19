<?php


namespace App\Exceptions\ApiExceptions;


use Exception;
use Throwable;

class ApiException extends Exception
{
    public function __construct($message = '', $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}