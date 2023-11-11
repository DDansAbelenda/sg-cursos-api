<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee_Edition extends Model
{
    use HasFactory;
    protected $fillable = ['edition_id', 'course_id'];

    public function employees()
    {
        $this->belongsTo(Employee::class);
    }
    
    public function editions()
    {
        $this->belongsTo(Edition::class);
    }
}
