<?php

namespace App\Http\Middleware;

use App\Models\Admin;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->bearerToken()) {
            return response()->json(['error' => 'Token is missing'], 401);
        }

        // token of header
        $token = $request->bearerToken();

        $admin = Admin::where('token', $token)->first();

        if (!$admin) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $next($request);
    }


    // public function handle($request, Closure $next)
    // {
    //     $admin = Admin::where('id', $request->user()->id)->first();
    //     // $admin = Admin::where('id', $request->id)->first();

    //     if ($admin) {
    //         return $next($request);
    //     }

    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    // public function handle($request, Closure $next)
    // {
    //     // Assuming you pass an 'admin_id' in the request
    //     $adminId = $request->input('admin_id');

    //     if (!$adminId) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     $admin = Admin::find($adminId);

    //     if ($admin) {
    //         return $next($request);
    //     }

    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    // public function handle(Request $request, Closure $next)
    // {
    //     // Check if the token is present in the request headers
    //     $token = $request->bearerToken();

    //     // Check if the token exists in any user's record
    //     $admin = Admin::where('token', $token)->first();
    //     if (!$admin) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     } else {
    //         // If admin found, proceed with the request
    //         return $next($request);
    //     }
    // }
}
