<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Satuans extends Model
{
    protected $table = 'satuan';
     public $timestamps = false;

    protected $fillable = [
        'satuan',
    ];
}
