<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    public function sendError($error, $errorMessage=[])
    {
        $response = [
            'success' => false,
            'message' => $error
        ];

        if(!empty($errorMessage)) {
            $response['data'] = $errorMessage;
        }
        return response()->json($response);
    }
}


