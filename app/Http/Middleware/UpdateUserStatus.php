<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UpdateUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth('api')->user();
            $user->update([
                'is_online' => true,
                'last_seen' => now(),
            ]);
        }

        return $next($request);
    }

    public function terminate($request, $response)
    {
        if (auth()->check()) {
            auth('api')->user()->update(['is_online' => false]);
        }
    }

}
