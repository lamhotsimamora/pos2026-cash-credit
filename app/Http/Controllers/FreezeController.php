<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FreezeController extends Controller
{
    public function index()
    {
         // get status of freeze
        $freeze = \App\Models\Freeze::select('is_frozen')->where('id', 1)->first();
        return $this->responseSuccess('Freeze or not !',$freeze);
    }
}
