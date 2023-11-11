<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'last_names',
        'address',
        'phone',
        'nif',
        'date_birth',
        'nationality',
        'salary',
        'sex',
        'is_qualified'
    ];

    public function editions()
    {
        return $this->hasMany(Edition::class);
    }
    
    public function employee_editions(){
        return $this->hasMany(Employee_Edition::class);
    }
}
