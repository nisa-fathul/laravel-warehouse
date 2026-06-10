<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRolesOrPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $rolesOrPermissions): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with([
                'notifikasi' => 'Please Login First!',
                'type' => 'warning',
            ]);
        }

        $user = Auth::user();

        // Support multiple role|permission seperti: kepala hrd|staff hrd|verifikasi cuti
        $items = explode('|', $rolesOrPermissions);

        // Cek apakah user memiliki salah satu role ATAU permission
        if (!$user->hasAnyRole($items) && !$user->hasAnyPermission($items)) {
            return redirect()->back()->with([
                'notifikasi' => 'You do not have the appropriate access!',
                'type' => 'warning'
            ]);
        }

        return $next($request);
    }
}
