<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;

class ResponseFormatter
{
    protected static $response = [
        'meta' => [
            'code' => 200,
            'status' => 'success',
            'message' => null
        ],
        'data' => null
    ];

    public static function success($data = null, $message = null)
    {
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public static function error($data = null, $message = null, $code = 400)
    {
        self::$response['meta']['status'] = 'error';
        self::$response['meta']['code'] = $code;
        self::$response['meta']['message'] = $message;
        self::$response['data'] = $data;

        return response()->json(self::$response, self::$response['meta']['code']);
    }

    public static function failedValidation($data = null)
    {
        self::$response['meta']['status'] = 'Unprocessable Content';
        self::$response['meta']['code'] = 422;
        self::$response['meta']['message'] = 'The given data was invalid';
        self::$response['data'] = $data;

        throw new HttpResponseException(
            response()->json(self::$response, self::$response['meta']['code'])
        );
    }
}
