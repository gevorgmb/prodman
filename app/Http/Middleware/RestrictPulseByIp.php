<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictPulseByIp
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Log::info($request->ip());
        $allowedIps = config('settings.trusted_client_ip_list');

        if (!in_array($request->ip(), $allowedIps)) {
            abort(403, 'Pulse is restricted to authorized IPs only.');
        }

        return $next($request);
    }
}
