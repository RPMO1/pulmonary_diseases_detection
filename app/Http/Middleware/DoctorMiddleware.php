<?php

namespace App\Http\Middleware;

use App\Models\Doctor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DoctorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Token is missing'], 401);
        }

        // token of header
        $token = $request->bearerToken();

        $doctor = Doctor::where('token', $token)->first();

        if (!$doctor) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }
}
