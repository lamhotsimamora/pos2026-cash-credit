<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->input('_token') !== 'd2a4a295847d2a4a295847b46c7469294eb767720a8b46c7469294eb767720a8') {
            return response()->json(['message' => 'Unauthorized'], 200);
        }
        return $next($request);
    }
}
