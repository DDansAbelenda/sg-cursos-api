<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Employee_Edition extends Pivot
{
    protected $table = "employee__editions";
    protected $fillable = ['edition_id', 'employee_id'];

}
