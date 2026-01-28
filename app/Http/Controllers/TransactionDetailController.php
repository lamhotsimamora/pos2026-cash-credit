<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionDetailController extends Controller
{
     public function load(Request $request)
    {
        $id_transaction =$request->input('id_transaction');

        $transaction = Transaction::where('id',$id_transaction)->first();
        $detail = \App\Models\view_transaction_detail::where('id_transaction',$id_transaction)->get();

        return $this->responseSuccess('Transaction details loaded successfully', array(
            'transaction' => $transaction,
            'details' => $detail
        ));
    }
}
