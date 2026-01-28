<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';
    public $timestamps = true;

      protected $fillable = [
        'name',
        'price_buy',
        'price_sell',
        'stock',
        'id_satuan',
        'profit'
    ];
}
