<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function response(array $data): JsonResponse
    {
        return response()->json($data);
    }

    public function error(string $error): JsonResponse
    {
        return response()->json(
                [
                    'error' => $error
                ]
            );
    }
}
