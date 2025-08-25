<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        // ตรวจสอบว่าผู้ใช้ login อยู่หรือไม่
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // แยก roles ที่ต้องการ
        $allowedRoles = explode(',', $roles);
        
        // ตรวจสอบว่าผู้ใช้มี role ที่ต้องการหรือไม่
        if (!in_array($request->user()->role, $allowedRoles)) {
            return redirect()->route('dashboard')
                ->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}
