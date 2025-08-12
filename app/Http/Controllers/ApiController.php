<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class ApiController extends Controller
{
    protected static function success($data = null): JsonResponse
    {
        return response()->json($data);
    }
}
