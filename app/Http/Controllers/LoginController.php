<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Doctor;
use App\Models\Admin;
use App\Models\Patient;
use App\traits\generalTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class LoginController extends Controller
{
    use generalTrait;
    public function admin_login(Request $request)
    {
        $user = Admin::where('email', $request->email)->first();

        // Check if a user with the provided email exists
        if (!$user) {
            return $this->returnErrorMessage('Invalid Email', 401);
        }

        // Verify the password
        if ($request->password != $user->password) {
            return response()->json(['message' => 'Invalid Password'], 401);
        }

        // Generate a token or perform any additional authentication logic
        $token = $user->createToken('admin')->accessToken;
        $user->token = $token;
        $user->save();
        $user->role = "admin";

        // Return the token to the client
        return $this->returnData((object)['admin_data' => $user], 'Logged in Succefully');
        // return response()->json(['message' => 'Logged in Succefully', 'admin_data' => $user]);
    }
    public function patient_login(Request $request)
    {
        $user = Patient::where('email', $request->email)->first();

        // Check if a user with the provided email exists
        if (!$user) {
            return $this->returnErrorMessage('Invalid email', 401);
        }

        // Verify the password
        // if ($request->password != $user->password) {
        if (!Hash::check($request->password, $user->password)) {
            return $this->returnErrorMessage('Invalid Password', 401);
        }

        // Generate a token or perform any additional authentication logic
        $token = $user->createToken('patient')->accessToken;
        // Add the token to the users object so it can be used in future requests
        $user->token = $token;
        $user->save();
        $user->role = "patient";
        // $user->save();


        // Return the token to the client
        return $this->returnData((object)['patient_data' => $user], 'Logged in Succefully');
        // return response()->json(['message' => 'Logged in Succefully', 'patient_data' => $user]);
    }
    public function doctor_login(Request $request)
    {
        $user = Doctor::where('email', $request->email)->first();

        // Check if a user with the provided email exists
        if (!$user) {
            return $this->returnErrorMessage('Invalid Email', 401);
            // return response()->json(['message' => 'Invalid Email'], 401);

        }

        // Verify the password
        if (!Hash::check($request->password, $user->password)) {
            // if ($request->password != $user->password) {
            return $this->returnErrorMessage('Invalid Password', 401);

            // return response()->json(['message' => 'Invalid Password'], 401);
        }

        // Generate a token or perform any additional authentication logic
        $token = $user->createToken('doctor')->accessToken;
        $user->token = $token;
        // $user->role="doctor";
        $user->save();
        $user->role = "doctor";


        // Return the token to the client
        return $this->returnData((object)['doctor_data' => $user], 'Logged in Succefully');
        // return response()->json(['message' => 'Logged in Succefully', 'doctor_data' => $user]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        // Check if the token exists in any user's record
        $user = Admin::where('token', $token)->first();
        if (!$user) {
            $user = Patient::where('token', $token)->first();
        }
        if (!$user) {
            $user = Doctor::where('token', $token)->first();
        }

        // If user with token found, revoke the token
        if ($user) {
            // Revoke token (if applicable)
            // Perform any additional logic based on your application's needs
            $user->token = null;
            $user->save();

            return $this->returnSuccessMessage('Successfully logged out', 201);
            // return response()->json(['message' => 'Successfully logged out']);
        }
        return $this->returnErrorMessage('Token not found', 401);

        // return response()->json(['message' => 'Token not found'], 401);
    }

    // public function logout(Request $request)
    // {
    //     // $user = $request->user();
    //     // dd($request->bearerToken());

    //     $user = Doctor::where('token', $request->bearerToken())->first();
    //     // dd($user);
    //     // $user = $request->user()->token()->revoke();
    //     // $user->tokens()->delete();
    //     if ($user) {
    //         $user->tokens()->each(function ($token) {
    //             $token->delete();
    //         });

    //         // Reset token to null
    //         $user->token = null;
    //         $user->save();

    //         return response()->json(['message' => 'Successfully logged out']);
    //     }

    // }


    // public function login(Request $request)
    // {
    //     $credentials = $request->only('id','email', 'password');

    //     // Attempt to authenticate doctor
    //     $doctor = Doctor::where('email', $credentials['email'])->first();
    //     if($doctor && Hash::check($credentials['password'], $doctor->password)){
    //     // if ($doctor && password_verify($credentials['password'], $doctor->password)) { 
    //         $token = Str::random(60); // Generate a random token
    //         $doctor->update(['token' =>  $token]); // Store token in database
    //         return response()->json(['token' => $token]);
    //     }

    //     // Attempt to authenticate patient
    //     $patient = Patient::where('email', $credentials['email'])->first();
    //     if ($patient && password_verify($credentials['password'], $patient->password)) {
    //         $token = Str::random(60); // Generate a random token
    //         $patient->update(['api_token' =>  $token]); // Store token in database
    //         return response()->json(['token' => $token]);
    //     }

    //     // Attempt to authenticate admin
    //     $admin = Admin::where('email', $credentials['email'])->first();
    //     if ($admin && password_verify($credentials['password'], $admin->password)) {
    //         $token = Str::random(60); // Generate a random token
    //         $admin->update(['api_token' =>  $token]); // Store token in database
    //         return response()->json(['token' => $token]);
    //     }

    //     // Authentication failed
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
}
