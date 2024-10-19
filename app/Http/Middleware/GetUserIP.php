<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GetUserIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // To capture user IP address
        $ip = app()->environment() == 'local' ? '8.8.8.8' : request()->ip();

        // To merge user IP address to request
        request()->merge(
            [
                'ip' => $ip
            ]
        );

        return $next($request);
    }
}
