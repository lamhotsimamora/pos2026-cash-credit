<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function load(Request $request)
    {
        $satuan = \App\Models\Satuans::all();
        return $this->responseSuccess('Satuan loaded successfully', $satuan);
    }

     public function save(Request $request){
        \App\Models\Satuans::create([
            'satuan' => $request->satuan
        ]);
        return $this->responseSuccess('Satuan saved successfully',null);
    }

     public function delete(Request $request){
        $category = \App\Models\Satuans::find($request->input('id'));
        if($category){
            $category->delete();
            return $this->responseSuccess('Satuan deleted successfully',null);
        }else{
            return $this->responseError('Satuan not found',null);
        }
    }
}
