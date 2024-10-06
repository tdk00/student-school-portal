<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

    class SchoolAdminMiddleware
    {
        public function handle(Request $request, Closure $next)
        {
            if (!Auth::guard('school')->check()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return $next($request);
        }
    }
