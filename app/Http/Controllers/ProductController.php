<?php

namespace App\Http\Controllers;

use App\Models\BarcodeProducts;
use App\Models\Products;
use App\Models\View_Barcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function load(Request $request)
    {
        $products = \App\Models\Products::orderBy('id', 'desc')->get();
        return $this->responseSuccess('Products loaded successfully', $products);
    }

    public function save(Request $request)
    {
        // check if name is exist
        $existingProduct = \App\Models\Products::where('name', $request->name)->first();
        if ($existingProduct) {
            return $this->responseError('Products name already exists', null);
        }

        DB::transaction(function () use ($request) {
            $product = Products::create([
                'name' => $request->name,
                'price_buy' => $request->price_buy,
                'price_sell' => $request->price_sell,
                'stock' => $request->stock,
                'id_satuan' => $request->id_satuan,
                'profit' => $request->price_sell - $request->price_buy,
            ]);

            
            foreach ($request->barcodes as $barcode) {
                BarcodeProducts::create([
                    'id_product' => $product->id,
                    'barcode' => $barcode
                ]);
            }
        });

        // \App\Models\Products::create([
        //      'code' => $request->code,
        //     'name' => $request->name,
        //     'price_buy' => $request->price_buy,
        //     'price_sell' => $request->price_sell,
        //     'stock' => $request->stock,
        //     'id_satuan' => $request->id_satuan,
        //     'id_category' => $request->id_category,
        //     'profit' => $request->price_sell - $request->price_buy
        // ]);
        return $this->responseSuccess('Products saved successfully', null);
    }

    public function allProfit()
    {
        $profit = DB::table('view_transaction_detail')
            ->where('payment_method', 'cash')
            ->selectRaw('SUM(qty_out * profit) as profit')
            ->value('profit');
        return $this->responseSuccess('Total profit retrieved successfully', $profit);
    }


    public function totalALlProfitCredit(){
         $profit = DB::table('view_transaction_detail')
            ->where('payment_method', 'credit')
            ->selectRaw('SUM(qty_out * profit) as profit')
            ->value('profit');
        return $this->responseSuccess('Total profit retrieved successfully', $profit);
    }

    public function delete(Request $request)
    {
        $product = \App\Models\Products::find($request->id);
        if ($product) {
            $product->delete();
            BarcodeProducts::where('id_product', $request->id)->delete();
            return $this->responseSuccess('Products deleted successfully', null);
        } else {
            return $this->responseError('Products not found', null);
        }
    }

    public function search(Request $request)
    {
       $query = $request->input('search');

        $product = View_Barcode::where(function ($q) use ($query) {
            $q->where('barcode', 'like', "%{$query}%")
            ->orWhere('name', 'like', "%{$query}%");
        })->get();

        return $this->responseSuccess('Products search successfully', $product);
    }

    public function total(Request $request)
    {
        $total = \App\Models\Products::count();
        return $this->responseSuccess('Total products retrieved successfully', $total);
    }

    public function edit(Request $request)
    {

        $id_edit = $request->input('id_product_edit');

        $product = Products::find($id_edit);

        $product->stock = $request->input('stock_edit');
        $product->price_sell = $request->input('price_sell_edit');
        $product->price_buy = $request->input('price_buy_edit');
        $product->name = $request->input('name_edit');

        $product->save();

        foreach ($request->input('barcodes_edit') as $barcode) {

            $data = BarcodeProducts::where('barcode', $barcode)
                    ->where('id_product', $id_edit)
                    ->get();

            if (count($data) == 0) {
                BarcodeProducts::create([
                    'id_product' => $id_edit,
                    'barcode' => $barcode
                ]);
            }
        }
        return $this->responseSuccess('Edit products retrieved successfully', null);
    }

    public function top()
    {
        $top = DB::table('view_transaction_detail')
            ->select(
                'name',
                DB::raw('SUM(qty_out) as total_sold')
            )
            ->groupBy('name')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        return $this->responseSuccess('Top products retrieved successfully', $top);
    }

    public function loadBarcode(Request $request)
    {
        $barcodes = BarcodeProducts::where('id_product', $request->id_product)
            ->pluck('barcode')->toArray();// 🔥 PENTING

        return $this->responseSuccess(
            'Barcodes loaded successfully',
            $barcodes
        );
    }
}
