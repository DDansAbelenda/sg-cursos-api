<?php

namespace App\Exceptions;
use Exception;
class EmployeeException extends Exception{
    public function __construct($message){
        parent::__construct($message);
    }
}