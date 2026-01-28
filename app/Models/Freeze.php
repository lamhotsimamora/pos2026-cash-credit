<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Freeze extends Model
{
     protected $table = 'freeze';
     public $timestamps = true;

    protected $fillable = [
        'is_frozen',
    ];
}
