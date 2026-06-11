<?php
// app/Http/Middleware/AdminMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini hanya untuk administrator.');
        }

        return $next($request);
    }
}

// ────────────────────────────────────────────────────────────────────────────
// Register in bootstrap/app.php (Laravel 12):
//
// ->withMiddleware(function (Middleware $middleware) {
//     $middleware->alias([
//         'admin' => \App\Http\Middleware\AdminMiddleware::class,
//     ]);
// })
// ────────────────────────────────────────────────────────────────────────────
