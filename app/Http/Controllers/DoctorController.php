<?php

namespace App\Http\Controllers;

use App\Http\Requests\storeDoctorRequest;
use App\Models\Doctor;
use App\Models\Patient;
use App\traits\generalTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use generalTrait;
    public function index()
    {
        $doctors = Doctor::all();
        return $this->returnData((object)['doctors' => $doctors]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function index($id)
    // {
    //     $doctor = Doctor::find($id);       //To get the patients of this doctor only (The logged in doctor) 
    //     if ($doctor) {
    //         // $patients = Patient::all()->where('doc_id', $id);     
    //         $patients= Patient::where('doc_id', $id)->get();
    //         if (count($patients) > 0) {
    //             return $this->returnData((object)['patients' => $patients]);
    //         } else {
    //             return $this->returnErrorMessage('Doctor Has No Patients', 404);
    //         }
    //     } else {
    //         return $this->returnErrorMessage('Invalid Doctor', 401);
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(storeDoctorRequest $request)
    {
        // $photoName = $this->uploadPhoto($request->photo, 'products');
        // $data = $request->except('photo');
        // $data['photo'] = $photoName;

        // dd($request->all());
        $doctor = new Doctor([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'years_of_experience' => $request->years_of_experience,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $doctor->save();
        // $doctor = Doctor::create($request->all());
        if ($doctor) {
            return $this->returnSuccessMessage("Doctor Successfully Created", 201);
        }
        return $this->returnErrorMessage("Doctor is Not Saved", 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $doctor = Doctor::find($id);
        if ($doctor) {
            return $this->returnData((object)['doctor' => $doctor]);
        } else {
            return $this->returnErrorMessage('Doctor not Found', 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return $this->returnErrorMessage('Doctor Not Found', 404);
        }
        return $this->returnData((object)['doctor' => $doctor]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'phone' => ['required', 'numeric'],
            'address' => ['required', 'string', 'min:3', 'max:255'],
            'years_of_experience' => ['required', 'numeric', 'min:1', 'max:50'],
            // 'email' =>['required', 'email', 'unique:doctors'],
            'password' => ['required', 'min:4', 'max:255']
        ]);
        if (Doctor::find($request->id)->email != $request->email) {
            $this->validate($request, [
                'email' => ['required', 'email', 'unique:doctors'],
            ]);
        }
        // if ($request->has('photo')) {
        //     $photoName = $this->uploadPhoto($request->photo, 'products');
        //     $data['photo'] = $photoName;
        // }
        // $doctor = Doctor::find($id);
        $doctor = $request->except(["_method"]);

        $doctor = Doctor::where('id', $request->id);

        $doctor = $doctor->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'years_of_experience' => $request->years_of_experience,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if (!$doctor) {
            return $this->returnError('400', 'The doctor is not updated');
        }

        // Doctor::where('id', $request->id)->update($doctor);
        return $this->returnSuccessMessage('Doctor Successfully Updated', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        if ($doctor) {
            $doctor->delete();
            // $photoPath = public_path('images\products\\' . $product->photo);
            // if (file_exists($photoPath)) {
            //     unlink($photoPath);
            // }
            return $this->returnSuccessMessage('Doctor Successfully Deleted');
        } else {
            return $this->returnErrorMessage('Doctor not Found', 404);
        }
    }
}
