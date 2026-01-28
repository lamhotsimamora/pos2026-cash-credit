<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentCredits extends Model
{
   protected $table = 'payment_credit';
    public $timestamps = true;

      protected $fillable = [
        'id_transaction',
        'amount_paid'
    ];
}
