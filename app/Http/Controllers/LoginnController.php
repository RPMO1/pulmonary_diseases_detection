<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User; // Make sure to import the User model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function admin_login(Request $request)
    {
        // $credentials = $request->only('email', 'password');
    
        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::admin();
            // $user = Auth::guard('admin')->user();
            $token = $user->createToken('Token Name')->accessToken;
            
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ]);
        }
        
        
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
    public function patient_login(Request $request)
    {
        // $credentials = $request->only('email', 'password');
    
         if (Auth::guard('patient')->attempt(['email' => $request->id, 'password' => $request->password])) {
            $user = Auth::patient();
            // $user = Auth::guard('admin')->user();
            $token = $user->createToken('Token Name')->accessToken;
            
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ]);
         }
        
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
    public function doctor_login(Request $request)
    {
        $credentials = $request->only('email', 'password');
         if (Auth::guard('doctor')->attempt($credentials)) {
            $user = Auth::doctor();
            // $user = Auth::guard('doctor')->user();
            $token = $user->createToken('Token Name')->accessToken;
            
            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
            ]);
         }
        
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
    
}
