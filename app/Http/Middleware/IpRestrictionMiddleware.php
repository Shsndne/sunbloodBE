<?php
// app/Http/Middleware/IpRestrictionMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IpRestrictionMiddleware
{
    /**
     * Daftar IP yang diizinkan mengakses halaman admin.
     * Tambahkan IP admin Anda di sini, atau set di .env
     *
     * Contoh .env:
     * ADMIN_ALLOWED_IPS=127.0.0.1,192.168.1.100
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil daftar IP dari .env, default hanya localhost
        $allowedIps = array_filter(
            array_map('trim', explode(',', env('ADMIN_ALLOWED_IPS', '127.0.0.1,::1')))
        );

        // Jika list IP kosong atau berisi '*', izinkan semua (mode development)
        if (empty($allowedIps) || in_array('*', $allowedIps)) {
            return $next($request);
        }

        $clientIp = $request->ip();

        if (!in_array($clientIp, $allowedIps)) {
            abort(403, "Akses dari IP {$clientIp} tidak diizinkan untuk halaman admin.");
        }

        return $next($request);
    }
}
