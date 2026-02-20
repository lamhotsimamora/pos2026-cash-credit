<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HistoryStockController extends Controller
{
      public function load(Request $request)
    {
        $stockOut = \App\Models\ViewHistoryStock::limit(200)->orderBy('history_stock_date', 'desc')->get();
        return $this->responseSuccess('History stock out loaded successfully', $stockOut);
    }

    public function search(Request $request){
        $query = $request->input('search');
        $historyStockOut = \App\Models\ViewHistoryStock::where('name', 'like', '%' . $query . '%')
           ->get();

        return $this->responseSuccess('History Stock Out loaded successfully', $historyStockOut);
    }
}
