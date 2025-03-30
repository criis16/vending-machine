<?php

namespace App\Application\Status\Exceptions;

use Exception;

class EmptyBalanceException extends Exception
{
    public function __construct(string $errorMessage = '')
    {
        parent::__construct($errorMessage);
    }
}
