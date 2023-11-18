<?php

namespace App\Exceptions;
use Exception;
class EditionExceptions extends Exception{
    public function __construct($message){
        parent::__construct($message);
    }
}