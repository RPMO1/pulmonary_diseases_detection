<?php

namespace App\Http\Controllers;

use App\Http\Requests\storePatientRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\traits\generalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use generalTrait;
    // public function index()
    // {
    //     $patients = Patient::all();
    //     return $this->returnData((object)['patients' => $patients]);
    // }
    public function index($id)
    {
        $doctor = Doctor::find($id);       //To get the patients of this doctor only (The logged in doctor) 
        if ($doctor) {
            // $patients = Patient::all()->where('doc_id', $id);     
            $patients= Patient::where('doc_id', $id)->get();
            if (count($patients) > 0) {
                return $this->returnData((object)['patients' => $patients]);
            } else {
                return $this->returnErrorMessage('Doctor Has No Patients', 404);
            }
        } else {
            return $this->returnErrorMessage('Invalid Doctor', 401);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storePatientRequest $request)
    {
        // dd($request->all());
        // $patient=Patient::create($request->all());
        $patient = new Patient([
            'fullName' => $request->fullName,
            'phone' => $request->phone,
            'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'doc_id' => $request->doc_id
        ]);

        $patient->save();
        if ($patient) {
            return $this->returnSuccessMessage("Patient Successfully Created", 210);
        }
        return $this->returnErrorMessage("Patient is not saved", 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $patient = Patient::find($id);
        if ($patient) {
            return $this->returnData((object)['patient' => $patient]);
        } else {
            return $this->returnErrorMessage('patient not found', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return $this->returnErrorMessage('Patient not found', 404);
        }
        return $this->returnData((object)['patient' => $patient]);
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'fullName' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'numeric'],
            'address' => ['required', 'string', 'min:3', 'max:255'],
            'age' => ['required', 'numeric'],
            'gender' => ['required', 'in:male,female'],
            //'email' =>['required', 'email','unique:patients'],
            'password' => ['required', 'min:4', 'max:255']
        ]);
        if (Patient::find($request->id)->email != $request->email) {
            $this->validate($request, [
                'email' => ['required', 'email', 'unique:patients'],
            ]);
        }
        $patient = $request->except(["_method"]);

        $patient = Patient::where('id', $request->id);

        $patient = $patient->update([
            'fullName' => $request->fullName,
            'phone' => $request->phone,
            'address' => $request->address,
            'age' => $request->age,
            'gender' => $request->gender,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // 'doc_id' => $request->doc_id
        ]);
        return $this->returnSuccessMessage('Patient Successfully Updated', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patient = Patient::find($id);
        if ($patient) {
            $patient->delete();
            return $this->returnSuccessMessage("Patient Successfully Deleted");
        } else {
            return $this->returnErrorMessage('patient not found', 404);
        }
    }
}
