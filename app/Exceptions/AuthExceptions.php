<?php

namespace App\Exceptions;

use Exception;

class AuthExceptions extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
