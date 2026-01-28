<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetails extends Model
{
    protected $table = 'transaction_detail';
     public $timestamps = true;

      protected $fillable = [
        'id_product',
        'qty_out',
        'price_sell_out',
        'id_transaction',
    ];
}
