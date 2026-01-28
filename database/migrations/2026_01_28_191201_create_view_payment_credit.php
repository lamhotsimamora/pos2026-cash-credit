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
            CREATE VIEW view_payment_credit AS
            select payment_credit.*,transaction.id_customer,transaction.payment_method,transaction.total_price,transaction.ppn,transaction.invoice,customer.name
            from payment_credit
            join transaction
            join customer
            where payment_credit.id_transaction = transaction.id and transaction.id_customer = customer.id     
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW view_payment_credit;');
    }
};
