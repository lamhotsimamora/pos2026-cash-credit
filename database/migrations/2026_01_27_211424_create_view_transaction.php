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
            CREATE VIEW view_transaction AS
            select transaction.*,customer.name as customer_name
            from transaction 
            join customer
            where transaction.id_customer = customer.id;          
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         DB::statement('DROP VIEW view_transaction;');
    }
};
