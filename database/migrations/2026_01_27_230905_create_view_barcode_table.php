<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement(<<<SQL
            CREATE VIEW view_barcode AS
            select products.*,barcode_products.barcode
            from products 
            join barcode_products
            where products.id = barcode_products.id_product;          
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          DB::statement('DROP VIEW view_barcode;');
    }
};
