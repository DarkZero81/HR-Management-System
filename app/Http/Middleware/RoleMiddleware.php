<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $roleName = strtolower((string) optional(Auth::user()->role)->role_name);
        $allowedRoles = array_map('strtolower', $roles);

        if ($roleName === '' || ! in_array($roleName, $allowedRoles, true)) {
            abort(403, 'ليس لديك الصلاحية اللازمة للوصول إلى هذه الصفحة.');
        }

        return $next($request);
    }
}
