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
            CREATE VIEW view_transaction_detail AS
            select view_product.*,transaction_detail.qty_out,transaction_detail.created_at as "created_at_detail",transaction_detail.id_transaction,transaction.payment_method 
            from view_product
            join transaction_detail
            join transaction
            where transaction.id = transaction_detail.id_transaction and
             view_product.id = transaction_detail.id_product         
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         DB::statement('DROP VIEW view_transaction_detail;');
    }
};
