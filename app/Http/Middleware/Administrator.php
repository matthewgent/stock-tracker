<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Administrator
{
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        $member = $request->user();
        if (! $member->isAdministrator()) {
            abort(403);
        }

        return $response;
    }
}
