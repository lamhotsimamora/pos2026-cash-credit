<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function responseSuccess( $message = "Success",$data)
    {
        return response()->json([
            'result' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public function responseError($message = "Error")
    {
        return response()->json([
            'result' => false,
            'message' => $message
        ], 200);
    }
}
