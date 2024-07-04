<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show($id)
    {
        // Retrieve the user by ID
        $user = User::find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Access the associated admin, doctor, and patient models
        $admin = $user->admin;
        $doctor = $user->doctor;
        $patient = $user->patient;

        // Accessing user from the Admin model
        $admin = Admin::find($id);
        $user = $admin->user;

        // Accessing user from the Doctor model
        $doctor = Doctor::find($id);
        $user = $doctor->user;

        // Accessing user from the Patient model
        $patient = Patient::find($id);
        $user = $patient->user;

        // Return the user and associated models as needed
        return response()->json([
            'user' => $user,
            'admin' => $admin,
            'doctor' => $doctor,
            'patient' => $patient
        ]);
    }
}
