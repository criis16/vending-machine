<?php

namespace App\Application\Status\Exceptions;

use Exception;

class BalanceNotSavedException extends Exception
{
    public function __construct(string $errorMessage = '')
    {
        parent::__construct($errorMessage);
    }
}
