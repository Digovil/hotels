<?php

namespace App\Services;

class ResponseFormatService
{
    public function success($data, $message = 'Success', $status = 200)
    {
        return response()->json([
            'status'  => true,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }

    public function error($message, $status = 400)
    {
        return response()->json([
            'status'  => false,
            'message' => $message,
        ], $status);
    }

    public function resp($data)
    {
        echo json_encode(array(
            "user" => $data->user,
            "token_uid" => $data->token
        ));
    }
}