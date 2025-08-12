<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Ticker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TickerController extends ApiController
{
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'search' => ['required', 'string', 'min:2'],
        ]);

        $searchText = $request->input('search');

        $tickers = Ticker::query()
            ->where('symbol', 'like', '%'.$searchText.'%')
            ->orWhere('name', 'like', '%'.$searchText.'%')
            ->with(['securityExchange', 'securityExchange.sovereignState'])
            ->limit(30)
            ->get();

        return self::success($tickers);
    }
}
