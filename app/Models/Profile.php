<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profile';
    public $timestamps = true;

     protected $fillable = [
        'name',
        'address',
        'hp',
        'email'
    ];
}
