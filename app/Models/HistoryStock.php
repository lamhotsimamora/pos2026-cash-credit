<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryStock extends Model
{
    protected $table = 'history_stock';
     public $timestamps = true;

     protected $fillable = [
        'product_id',
        'qty_out'
    ];
}
