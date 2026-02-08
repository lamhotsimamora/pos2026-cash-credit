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

    /*
    |--------------------------------------------------------------------------
    | 1️⃣ MONTHLY DATA (PER BULAN)
    |--------------------------------------------------------------------------
    */

    // CASH
    $cashRaw = Transaction::where('payment_method', 'cash')
        ->whereYear('created_at', $year)
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('total', 'month');

    // CREDIT
    $creditRaw = Transaction::where('payment_method', 'credit')
        ->whereYear('created_at', $year)
        ->selectRaw('MONTH(created_at) as month, SUM(total_price) as total')
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('total', 'month');

    $monthlyLabels     = [];
    $monthlyCashData   = [];
    $monthlyCreditData = [];

    for ($m = 1; $m <= 12; $m++) {
        $monthlyLabels[]     = Carbon::create()->month($m)->translatedFormat('M');
        $monthlyCashData[]   = (int) ($cashRaw[$m] ?? 0);
        $monthlyCreditData[] = (int) ($creditRaw[$m] ?? 0);
    }

    /*
    |--------------------------------------------------------------------------
    | 2️⃣ DAILY DATA (7 HARI TERAKHIR)
    |--------------------------------------------------------------------------
    */

    $startDate = Carbon::now()->subDays(6)->startOfDay();
    $endDate   = Carbon::now()->endOfDay();

    $dailyRaw = Transaction::selectRaw("
            DATE(created_at) as date,
            SUM(CASE WHEN payment_method = 'cash' THEN total_price ELSE 0 END) as cash,
            SUM(CASE WHEN payment_method = 'credit' THEN total_price ELSE 0 END) as credit
        ")
        ->whereBetween('created_at', [$startDate, $endDate])
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date');

    $dailyLabels     = [];
    $dailyCashData   = [];
    $dailyCreditData = [];

    for ($i = 6; $i >= 0; $i--) {
        $date  = Carbon::now()->subDays($i)->format('Y-m-d');
        $label = Carbon::now()->subDays($i)->translatedFormat('D');

        $dailyLabels[]     = $label;
        $dailyCashData[]   = isset($dailyRaw[$date]) ? (int) $dailyRaw[$date]->cash : 0;
        $dailyCreditData[] = isset($dailyRaw[$date]) ? (int) $dailyRaw[$date]->credit : 0;
    }

    /*
    |--------------------------------------------------------------------------
    | 3️⃣ RESPONSE (FINAL – AMAN FRONTEND)
    |--------------------------------------------------------------------------
    */

    return $this->responseSuccess(
        'Transaction graphic loaded successfully',
        [
            'year'  => $year,
            'month' => $month,

            // MONTHLY
            'monthly' => [
                'labels' => $monthlyLabels,
                'datasets' => [
                    [
                        'label' => 'Cash',
                        'data'  => $monthlyCashData
                    ],
                    [
                        'label' => 'Credit',
                        'data'  => $monthlyCreditData
                    ]
                ]
            ],

            // DAILY
            'daily' => [
                'labels' => $dailyLabels,
                'datasets' => [
                    [
                        'label' => 'Cash',
                        'data'  => $dailyCashData
                    ],
                    [
                        'label' => 'Credit',
                        'data'  => $dailyCreditData
                    ]
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

        return $this->responseSuccess('Transaction saved successfully', $data);
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
