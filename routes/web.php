<?php

use App\Http\Controllers\FreezeController;
use App\Models\Profile;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\TransactionDetails;
use App\Models\view_transaction;
use App\Models\view_transaction_detail;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/report/invoice/{id}', function ($id) {
    $profile = Profile::where('id', 1)->get();

    $invoice = view_transaction::where('id', $id)->get();

    $detail = view_transaction_detail::where('id_transaction', $id)->get();

    $data = array(
        'id' => $id,
        'profile' => $profile[0],
        'invoice' => $invoice[0],
        'detail' => $detail,
        'ppn' => $invoice[0]->ppn,
        'total_after_ppn' => $invoice[0]->total_price,
        'customer'=> $invoice[0]->customer_name,
        'type_payment'  => $invoice[0]->payment_method,
    );

    return view('invoice', $data);
});

Route::get('/report/invoice/sale/month', function () {
    $profile = Profile::where('id', 1)->get();
    $transaction = view_transaction::whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->get();

    $total_transaction = $transaction->sum('total_price');

    $data = array(
        'profile' => $profile[0],
        'transaction' => $transaction,
        'date' => date('M') . '-' . date('Y'),
        'now' => date('d-M-Y'),
        'total' => $total_transaction,
        'description'=> 'Monthly Sales Report'
    );
    return view('laporan', $data);
});



Route::get('/report/invoice/sale/week', function () {
    $profile = Profile::where('id', 1)->get();
   $transaction = view_transaction::whereBetween('created_at', [
        Carbon::now()->startOfWeek(), 
        Carbon::now()->endOfWeek()
    ])
    ->get();

    $total_transaction = $transaction->sum('total_price');

    $data = array(
        'profile' => $profile[0],
        'transaction' => $transaction,
        'date' => date('M') . '-' . date('Y'),
        'now' => date('d-M-Y'),
        'total' => $total_transaction,
        'description'=> 'Weekly Sales Report'
    );
    return view('laporan', $data);
});

