<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    protected $table = 'customer';
    public $timestamps = true;

      protected $fillable = [
        'name',
        'phone',
        'address'
    ];
}
