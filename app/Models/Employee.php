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

    protected $casts = [
        'date_birth' => 'datetime:d/m/Y',
    ];

    public function editions()
    {
        return $this->hasMany(Edition::class);
    }
    
    public function edition_students(){
        return $this->belongsToMany(Edition::class,'employee__editions');
    }
}
