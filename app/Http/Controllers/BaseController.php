<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    public function response(): JsonResponse
    {
        return response()->json();
    }

    public function error(): JsonResponse
    {
        return response()->json();
    }
}
