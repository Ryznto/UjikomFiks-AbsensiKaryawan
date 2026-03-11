<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
public function handle(Request $request, Closure $next, string ...$roles): Response
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $userRole = strtolower(Auth::user()->role);

    foreach ($roles as $role) {
        if ($userRole === strtolower(trim($role))) {
            return $next($request);
        }
    }

    abort(403, 'Akses ditolak!');
}
}