<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    use generalTrait;
    public function create($id)         //To get the patients of this doctor only (The logged in doctor)                
    {
        $doctor = Doctor::find($id);
        if ($doctor) {
            $patients = Patient::select('id', 'fullName', 'doc_id')->where('doc_id', $id)->orderBy('fullName')->get();     //select list
            if (count($patients) > 0) {
                return $this->returnData((object)['patients' => $patients]);
            } else {
                return $this->returnErrorMessage('Doctor Has No Patients', 404);
            }
        } else {
            return $this->returnErrorMessage('Invalid Doctor', 401);
        }
    }

    public function store(Request $request)
    {
        $rules = [
            'refollow_date' => 'required|date|after:today',
            'patient_id' => 'required',
            'doc_id' => 'required'
        ];

        $request->validate($rules);

        $appointment = Appointment::create([
            'patient_id' => $request->patient_id,
            'refollow_date' => $request->refollow_date,
            'doc_id' => $request->doc_id,
        ]);
        if ($appointment) {
            return $this->returnSuccessMessage("Appointment Successfully Created", 201);
        }
        return $this->returnErrorMessage("Appointment is Not Saved", 400);
    }
    public function doctor_appointments($id)
    {
        $doctor = Doctor::find($id);
        if ($doctor) {
            $appointments = Appointment::where('doc_id', $doctor->id)->get(['id', 'refollow_date', 'patient_id']);
            // $patients = Patient::where('doc_id', $doctor->id)->get(['id', 'fullName']);
            $data = [
                // 'date' => Carbon::now()->format('Y-m-d h:i:s'),
                'doctor_name' => $doctor->name,
                'refollow_dates' => []
            ];
            if (count($appointments) > 0) {
                foreach ($appointments as $key => $appointment) {
                    $patient = Patient::find($appointment->patient_id);
                    $data['refollow_dates'][] = [
                        'appointment_id' => $appointment->id,
                        'patient_id' => $patient->id,
                        'patient_name' => $patient->fullName,
                        'refollow_date' => $appointment->refollow_date,
                    ];
                }
            } else {
                $data['refollow_dates'] = "No Appointments Found";
            }

            return $this->returnData((object) $data);
        } else {
            return $this->returnErrorMessage('Invalid Doctor', 404);
        }
    }
    public function patient_appointment($id)
    {
        $patient = Patient::find($id);
        if ($patient) {
            $doctor = Doctor::find($patient->doc_id);
            $appointments = Appointment::where('patient_id', $patient->id)->get();
            if (count($appointments) > 0) {
                $data = [
                    // 'date' => Carbon::now()->format('Y-m-d h:i:s'),
                    'doctor_name' => $doctor->name,
                    'patient_id' => $patient->id,
                    'patient_name' => $patient->fullName,
                    'appintment_id' => $appointments->last()->id,
                    'refollow_date' => $appointments->last()->refollow_date,
                    // 'refollow_dates' => []
                ];
                // foreach ($appointments as $key => $appointment) {
                //     // $data['refollow_dates'][] = $appointment->refollow_date;   // All refollow dates
                //     $data['refollow_dates'][$key] =
                //         [
                //             'appointment_id' => $appointment->id,
                //             'refollow_date' => $appointment->refollow_date,
                //         ];
                // }

                return $this->returnData((object) $data);
            } else {
                return $this->returnErrorMessage('No Appointments Found', 404);
            }
        } else {
            return $this->returnErrorMessage('Invalid Patient', 401);
        }
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->delete();

            return $this->returnSuccessMessage('Appointment Successfully Deleted');
        } else {
            return $this->returnErrorMessage('Appointment not Found', 404);
        }
    }
}
