<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transaction';
    public $timestamps = true;

     protected $fillable = [
        'invoice',
        'total_price',
        'ppn',
        'payment_method',
        'id_customer',
        'additional_price'
    ];
}
