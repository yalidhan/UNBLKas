<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PeriodClosing;
class AutoCloseExpiredPeriods
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        PeriodClosing::whereNotNull('reopen_expires_at')
        ->where('reopen_expires_at', '<', now())
        ->update([
            'reopen_expires_at' => null
        ]);


        return $next($request);
    }
}
