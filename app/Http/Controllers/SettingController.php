<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function savePPN(Request $request)
    {
        $ppn = $request->input('ppn');

        Settings::where('id', 1)->update(['ppn' => $ppn]);
        return $this->responseSuccess('PPN updated successfully',null);
    }

    public function loadPPN(Request $request)
    {
        $setting = Settings::where('id', 1)->first();

        return $this->responseSuccess('PPN loaded successfully', $setting['ppn']);
    }
}
