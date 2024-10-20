<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{    
    /**
     * Return response
     *
     * @param  array $data
     * @return JsonResponse
     */
    public function response(array $data): JsonResponse
    {
        return response()->json($data);
    }
    
    /**
     * Return error response
     *
     * @param  string $error
     * @param  int $statusCode
     * @return JsonResponse
     */
    public function error(string $error, int $statusCode = 500): JsonResponse
    {
        return response()->json(
                [
                    'error' => $error
                ],
                $statusCode
            );
    }
}
