<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (!$user || ($user->role ?? null) !== 'admin') {
            abort(403, 'Hanya admin yang boleh mengakses.');
        }
        return $next($request);
    }
}
