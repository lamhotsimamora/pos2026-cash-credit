<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Settings;
use App\Models\Transaction;
use App\Models\TransactionDetails;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $datePart = date('Ymd');
        $randomPart = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return $prefix . $datePart . $randomPart;
    }

    public function load(Request $request)
    {
        $query = \App\Models\view_transaction::query();

        // FILTER BULAN (01 - 12)
        if ($request->filled('month')) {
            $query->whereMonth('created_at', $request->month);
        }

        $transaction = $query
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get();

        return $this->responseSuccess(
            'Transaction loaded successfully',
            $transaction
        );
    }

   public function loadGraphicCashCredit(Request $request)
{
    $year  = $request->year ?? date('Y');
    $month = date('m');

    /**
     * =========================
     * 1️⃣ DATA CASH
     * =========================
     */
    $cashRaw = Transaction::where('payment_method', 'cash')
        ->whereYear('created_at', $year)
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('total', 'month');

    /**
     * =========================
     * 2️⃣ DATA CREDIT
     * =========================
     */
    $creditRaw = Transaction::where('payment_method', 'credit')
        ->whereYear('created_at', $year)
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('total', 'month');

    /**
     * =========================
     * 3️⃣ BUILD LABEL & DATASET
     * =========================
     */
    $labels      = [];
    $cashData    = [];
    $creditData  = [];

    for ($m = 1; $m <= 12; $m++) {
        $labels[]     = Carbon::create()->month($m)->translatedFormat('M');
        $cashData[]   = (int) ($cashRaw[$m] ?? 0);
        $creditData[] = (int) ($creditRaw[$m] ?? 0);
    }

    /**
     * =========================
     * RESPONSE
     * =========================
     */
    return $this->responseSuccess(
        'Transaction graphic loaded successfully',
        [
            'year'  => $year,
            'month'=> $month,
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Cash',
                    'data'  => $cashData
                ],
                [
                    'label' => 'Credit',
                    'data'  => $creditData
                ]
            ]
        ]
    );
}


    public function save(Request $request)
    {
        $total_price = $request->input('total_price');
        $ppn = Settings::where('id', 1)->first()->ppn;

        $data = \App\Models\Transaction::create([
            'invoice' => $this->generateInvoiceNumber(),
            'ppn' => $ppn,
            'total_price' => ($total_price * $ppn / 100) + $total_price,
            'payment_method' => $request->input('payment_type'),
            'id_customer' => $request->input('customer_id')
        ]);

        for ($i = 0; $i < count($request->input('data')); $i++) {
            $qty_out = $request->input('data')[$i]['qty'];
            $id_product = $request->input('data')[$i]['id'];
            \App\Models\TransactionDetails::create([
                'id_product' => $id_product,
                'qty_out' => $qty_out,
                'price_sell_out' => $request->input('data')[$i]['price_sell'],
                'id_transaction' => $data->id
            ]);

            Products::where('id', $id_product)
                ->decrement('stock', $qty_out);
        }

        return $this->responseSuccess('Transaction saved successfully', null);
    }

    public function total(Request $request)
    {
        $total = \App\Models\Transaction::count();
        return $this->responseSuccess('Total transactions retrieved successfully', $total);
    }

   

    public function totalToday(Request $request)
    {
        $total = \App\Models\Transaction::
            where('payment_method', 'cash')
            ->whereDate('created_at', date('Y-m-d'))
            ->sum('total_price');
        return $this->responseSuccess('Total transactions today retrieved successfully', $total);
    }

    public function totalTodayCredit(Request $request)
    {
        $total = \App\Models\Transaction::
            where('payment_method', 'credit')
            ->whereDate('created_at', date('Y-m-d'))
            ->sum('total_price');
        return $this->responseSuccess('Total transactions today retrieved successfully', $total);
    }


    public function totalMonth(Request $request)
    {
        $total = \App\Models\Transaction::where('payment_method', 'cash')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_price');
        return $this->responseSuccess('Total transactions Month retrieved successfully', $total);
    }

    public function totalMonthCredit(Request $request)
    {
        $total = \App\Models\Transaction::where('payment_method', 'credit')
            ->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'))
            ->sum('total_price');
        return $this->responseSuccess('Total transactions Month retrieved successfully', $total);
    }

    public function delete(Request $request)
    {
        $transaction_detail = TransactionDetails::where('id_transaction', $request->input('id_transaction'));
        $transaction_detail->delete();

        $transaction = Transaction::find($request->input('id_transaction'));
        $transaction->delete();

        return $this->responseSuccess('Transaction delete successfully', null);
    }

    public function search(Request $request)
    {
        $query = $request->input('invoice');
        $transactions = \App\Models\view_transaction::where('invoice', 'like', '%' . $query . '%')
            ->orWhere('customer_name', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->get();

        return $this->responseSuccess('Transactions loaded successfully', $transactions);
    }
}
