<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name','description','number_hours','cost'] ;

    public function editions(){
        return $this->hasMany(Edition::class);
    }
}
