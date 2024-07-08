<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) return redirect()->route('login');

        $role = Auth::user()->tipe;
        if (in_array($role, $roles)) {
            $bagian = $request->route('bagian');
            if ($bagian && !in_array($role, ['Super Admin', 'Staff']) && $role != "Admin $bagian") {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke menu tersebut.');
            }
            return $next($request);
        }

        return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki akses ke menu tersebut.');
    }
}
