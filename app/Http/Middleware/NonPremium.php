<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class NonPremium
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        $member = member();
        if ($member !== null and $member->hasPremium()) {
            abort(403);
        }

        return $response;
    }
}
