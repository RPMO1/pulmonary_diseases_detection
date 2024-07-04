<?php

use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PatientController;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'admin'], function () {
    // admin routes
});

Route::group(['middleware' => 'doctor'], function () {
    // Doctor routes
});

Route::group(['middleware' => 'patient'], function () {
    // Patient routes
});

Route::post('admin_login', [LoginController::class, 'admin_login']);
Route::post('patient_login', [LoginController::class, 'patient_login']);
Route::post('doctor_login', [LoginController::class, 'doctor_login']);
Route::post('logout', [LoginController::class, 'logout']);


Route::get('doctors/all', [DoctorController::class, 'index']);
// Route::get('doctors/doctor_patients/{id}', [DoctorController::class, 'doctor_patients']);
Route::get('doctors/{id}/show', [DoctorController::class, 'show']);
Route::post('doctors/store', [DoctorController::class, 'store']);
Route::get('doctors/{id}/edit', [DoctorController::class, 'edit']);
Route::put('doctors/update', [DoctorController::class, 'update']);
Route::delete('doctors/destroy/{id}', [DoctorController::class, 'destroy']);

Route::get('patients/all/{id}', [PatientController::class, 'index']);
Route::get('patients/{id}/show', [PatientController::class, 'show']);
Route::post('patients/store', [PatientController::class, 'store']);
Route::get('patients/{id}/edit', [PatientController::class, 'edit']);
Route::put('patients/update', [PatientController::class, 'update']);
Route::delete('patients/destroy/{id}', [PatientController::class, 'destroy']);



// Route::group(['prefix' => 'report'], function () {
Route::post('report/store_result', [ReportController::class, 'store_result']);
Route::get('report/upload_audio/{id}', [ReportController::class, 'upload_audio']);
Route::get('report/doctor_report/{id}', [ReportController::class, 'doctor_report']);
Route::get('report/patient_report/{id}', [ReportController::class, 'patient_report']);
// Route::post('report/store_medications', [ReportController::class, 'store_medications']);

// });


Route::get('appointments/create/{id}', [AppointmentsController::class, 'create']);
Route::post('appointments/store', [AppointmentsController::class, 'store']);
Route::get('appointments/doctor_appointments/{id}', [AppointmentsController::class, 'doctor_appointments']);
Route::get('appointments/patient_appointment/{id}', [AppointmentsController::class, 'patient_appointment']);
Route::delete('appointments/destroy/{id}', [AppointmentsController::class, 'destroy']);

