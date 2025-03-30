<?php

namespace App\Application\Coin\Exceptions;

use Exception;

class CoinsNotReturnException extends Exception
{
    public function __construct(string $errorMessage = '')
    {
        parent::__construct($errorMessage);
    }
}
