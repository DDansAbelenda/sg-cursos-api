<?php

namespace App\Exceptions;
use Exception;
class CourseExceptions extends Exception{
    public function __construct($message){
        parent::__construct($message);
    }
}