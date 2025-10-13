<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Role
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check() || !$request->user()->hasAnyRole($roles)) {
            abort(403, 'ACESSO NÃO AUTORIZADO.');
        }

        return $next($request);
    }
}