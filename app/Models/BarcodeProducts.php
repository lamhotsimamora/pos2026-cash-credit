<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarcodeProducts extends Model
{
     protected $table = 'barcode_products';
    public $timestamps = true;
    protected $fillable = ['id_product','barcode'];
}
