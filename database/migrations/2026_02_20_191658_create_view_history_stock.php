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
            CREATE VIEW view_history_stock AS
            select products.*,history_stock.qty_out,history_stock.created_at as history_stock_date
            from products
            join history_stock
            where history_stock.product_id = products.id
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         DB::statement('DROP VIEW view_history_stock;');
    }
};
