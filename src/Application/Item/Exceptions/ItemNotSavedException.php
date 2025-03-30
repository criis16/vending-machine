<?php

namespace App\Application\Item\Exceptions;

use Exception;

class ItemNotSavedException extends Exception
{
    public function __construct(string $errorMessage = '')
    {
        parent::__construct($errorMessage);
    }
}
