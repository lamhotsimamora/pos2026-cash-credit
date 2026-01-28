<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class CustomerController extends Controller
{
    public function load(Request $request)
    {
        $customers = \App\Models\Customers::orderBy('id', 'desc')->get();
        return $this->responseSuccess('Customers loaded successfully', $customers);
    }

    public function save(Request $request)
    {
        // check if name is exist
        $existingCustomer = \App\Models\Customers::where('name', $request->name)->first();
        if ($existingCustomer) {
            return $this->responseError('Customer name already exists', null);
        }

        \App\Models\Customers::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        return $this->responseSuccess('Customer saved successfully', null);
    }


    public function update(Request $request)
    {
        $customer = \App\Models\Customers::find($request->id);
        if (!$customer) {
            return $this->responseError('Customer not found', null);
        }

        $customer->name = $request->name;
        $customer->phone = $request->phone;
        $customer->address = $request->address;
        $customer->save();

        return $this->responseSuccess('Customer updated successfully', null);
    }


    public function delete(Request $request)
    {
        $customer = \App\Models\Customers::find($request->id);
        if ($customer) {
            $customer->delete();
            return $this->responseSuccess('Customer deleted successfully', null);
        } else {
            return $this->responseError('Customer not found', null);
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('search');
        $customers = \App\Models\Customers::where('name', 'like', '%' . $query . '%')
            ->orWhere('phone', 'like', '%' . $query . '%')
            ->orWhere('address', 'like', '%' . $query . '%')
            ->orderBy('id', 'desc')
            ->get();

        return $this->responseSuccess('Customers loaded successfully', $customers);
    }

    public function payCredit(Request $request)
    {
        $id_transaction = $request->input('id_transaction');
        $amount_paid = $request->input('amount_paid');

        \App\Models\PaymentCredits::create([
            'id_transaction' => $id_transaction,
            'amount_paid' => $amount_paid
        ]);

        // change payment_method to cash
        Transaction::find($id_transaction)->update([
            'payment_method' => 'cash'
        ]);

        return $this->responseSuccess('Payment credit saved successfully', null);
    }

    public function paymentHistory(Request $request)
    {
        $data = DB::table('view_payment_credit')
            ->where('id_customer', $request->customer_id)
            ->orderBy('created_at', 'desc')
            ->get();

      return $this->responseSuccess('Payment history retrieved successfully', $data);
    }
}
