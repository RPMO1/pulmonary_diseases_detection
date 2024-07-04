<?php

namespace App\Http\Middleware;

use App\Models\Patient;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Token is missing'], 401);
        }

        // token of header
        $token=$request->bearerToken();

        $patient = Patient::where('token', $token)->first();

        if (!$patient) {
            return response()->json(['error' => 'Unauthorized'], 401);
           
        }
        return $next($request);
        
    }
}
