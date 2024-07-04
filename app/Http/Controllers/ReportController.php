<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Medication;
use App\Models\Patient;
use App\Models\Result;
use App\traits\generalTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    use generalTrait;
    public function upload_audio($id)
    {
        $doctor = Doctor::find($id);       //To get the patients of this doctor only (The logged in doctor) 
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

    public function store_result(Request $request)
    {
        // validation
        $rules = [
            'audio_path' => 'mimes:audio/mpeg,mpga,mp3,wav,aac',
            'patient_id' => 'required'
        ];

        $request->validate($rules);

        $path = $this->uploadAudio($request->audio_path, "");   // Function to Upload Image and Return The Path Of This File In Storage

        // $path = $request->audio_path->store('public/audio');
        // return $path;

        // link model and return result 
        $response = Http::post('http://localhost:5000/predict', [
            "audio_path" => public_path('audios/' . $path)

            // "audio_path" =>"F:/grad_project/public/audios/" . $path
            // $imagesPath = public_path('images');
        ]);

        // Handle the prediction response
        $result = $response['predictions'];

        // insert data into database
        $data = Result::create([
            "result" => $result,
            "patient_id" => $request->patient_id,
        ]);
        return $this->returnData((object) $data);
    }
    public function doctor_report($id)
    {
        // Find the doctor by ID
        $doctor = Doctor::find($id);

        // Check if doctor exists
        if (!$doctor) {
            return $this->returnErrorMessage('No Reports Found', 404);
        }

        // Retrieve the patients for the doctor
        $patients = Patient::where('doc_id', $doctor->id)->get(['id', 'fullName']);

        // Initialize the report array
        $report = [
            'doctor_name' => $doctor->name,
            'patients' => [],
        ];

        // Iterate through each patient
        foreach ($patients as $patient) {
            // Retrieve all diagnoses for the patient
            $diagnosis = Result::where('patient_id', $patient->id)->get();

            // Only add patient to the report if they have diagnoses
            if ($diagnosis->isNotEmpty()) {
                // Initialize the patient's report entry
                $patientReport = [
                    'patient_name' => $patient->fullName,
                    'patient_id' => $patient->id,
                    'diagnosis' => $diagnosis->last()->result,
                    'date' => $diagnosis->last()->created_at->format('Y-m-d h:i:s'),
                ];

                // Iterate through each diagnosis
                // foreach ($diagnoses as $diagnosis) {
                //     $patientReport['diagnosis'] = [
                //         'result' => $diagnosis->result,
                //         'date' => $diagnosis->created_at->format('Y-m-d h:i:s'),
                //     ];
                // }

                // Add the patient's report entry to the main report
                $report['patients'][] = $patientReport;
            }
        }

        // Return the report data
        return $this->returnData((object) $report);
    }

    public function patient_report($id)
    {
        $patient = Patient::find($id);
        if ($patient) {
            $doctor = Doctor::find($patient->doc_id);
            // $medications = Medication::where('patient_id', $patient->id)->get();
            $diagnosis = Result::where('patient_id', $patient->id)->get();
            // return $diagnosis;
            $report = [
                'patient_name' => $patient->fullName,
                'patient_id' => $patient->id,
                'doctor_name' => $doctor->name,
                // 'result' => $diagnosis->result,
                'diagnosis' => $diagnosis->last()->result,
                'date' => $diagnosis->last()->created_at->format('Y-m-d h:i:s'),          //diagnosis date for this patient
                // 'date' => $medications->last()->created_at->format('Y-m-d h:i:s'),          //last prescription date for this patient
            ];
            // return $report;
            // foreach ($diagnosis as $key3 => $diagnosis) {
            //     $report['diagnosis'][] = $diagnosis->result;
            // }
            return $this->returnData((object) $report);
        } else {
            return $this->returnErrorMessage('Invalid Patient', 404);
        }
    }





    // public function doctor_report($id)
    // {
    //     $doctor = Doctor::find($id);
    //     if ($doctor) {
    //         $patients = Patient::where('doc_id', $doctor->id)->get(['id', 'fullName']);
    //         $report = [
    //             'doctor_name' => $doctor->name,
    //             'patients' => [],
    //         ];
    //         foreach ($patients as $key2 => $patient) {
    //             // $medications = Medication::where('patient_id', $patient->id)->get();
    //             $diagnosis = Result::where('patient_id', $patient->id)->get();

    //             $report['patients'][$key2] = [
    //                 'patient_name' => $patient->fullName,
    //                 'patient_id' => $patient->id,
    //                 'diagnosis' => [],
    //                 // 'date' => [],

    //                 // 'diagnosis' => $diagnosis->last()->result,
    //                 // 'date' => $diagnosis->last()->created_at->format('Y-m-d h:i:s'),          //diagnosis date for this patient

    //                 // 'medications' => [],
    //                 // 'date'=>$medications[0]->created_at->format('Y-m-d h:i:s'),            //first prescription date for this patient
    //                 // 'date' => Carbon::now()->format('Y-m-d h:i:s'),
    //             ];
    //             foreach ($diagnosis as $key3 => $diagnoses) {
    //                 // $report['patients'][$key2]['medications']=                  //Get last prescription only
    //                 $report['patients'][$key2]['diagnosis'] =            //Keep all prescriptions  for each patient.
    //                     // $medication->medications;
    //                     $diagnoses->result;
    //                 // 'date' => $diagnosis->last()->created_at->format('Y-m-d h:i:s'), 
    //             };
    //             // foreach ($date as $key4 => $date) {
    //             //     $report['patients'][$key2]['date'] = 
    //             //     $date->created_at->format('Y-m-d h:i:s');

    //             // }


    //             // } else {
    //             //     $report['patients'][$key2] = [
    //             //         'patient_name' => $patient->fullName,
    //             //         'patient_id' => $patient->id,
    //             //     ];




    //             //     foreach ($medications as $key3 => $medication) {
    //             //         // $report['patients'][$key2]['medications']=                  //Get last prescription only
    //             //         $report['patients'][$key2]['medications'][] =            //Keep all prescriptions  for each patient.
    //             //             $medication->medications;
    //             // }
    //         }
    //         return $this->returnData((object) $report);
    //     } else {
    //         return $this->returnErrorMessage('No Reports Found', 404);
    //     }
    // }

    // $audioPath = "F:\grad_project/" . $path;
    // $audio = $request->file('audio');
    // $response = Http::attach(
    //     'audio_path',
    //     file_get_contents($audioPath),
    //     $audio->getClientOriginalName()
    // )->post('http://127.0.0.1:5000/predict');

    // public function create_prescription($id)
    // {
    //     $doctor = Doctor::find($id);       //To get the patients of this doctor only (The logged in doctor) 
    //     if ($doctor) {
    //         $patients = Patient::select('id', 'fullName', 'doc_id')->where('doc_id', $id)->orderBy('fullName')->get();     //select list
    //         if (count($patients) > 0) {
    //             return $this->returnData((object)['patients' => $patients]);
    //         } else {
    //             return $this->returnErrorMessage('Doctor Has No Patients', 404);
    //         }
    //     } else {
    //         return $this->returnErrorMessage('Invalid Doctor', 401);
    //     }
    // }

    // public function store_medications(Request $request)
    // {
    //     $rules = [
    //         'medications' => 'required|min:3|max:1000',
    //         'patient_id' => 'required'                                              //hidden input from the previous function
    //     ];

    //     $request->validate($rules);
    //     $medications = Medication::create($request->all());
    //     if ($medications) {
    //         return $this->returnSuccessMessage("Prescription Successfully Created", 201);
    //     }
    //     return $this->returnErrorMessage("Prescription is Not Saved", 400);
    // }
}
