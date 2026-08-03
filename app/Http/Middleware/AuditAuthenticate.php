<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditAuthenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('audit_user_id')) {
            return redirect('/audit/login');
        }

        return $next($request);
    }
}