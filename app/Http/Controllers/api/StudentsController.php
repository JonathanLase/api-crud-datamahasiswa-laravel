<?php

namespace App\Http\Controllers\api;

use App\Models\Students;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StudentsResource;
use Illuminate\Support\Facades\Validator;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $students = Students::all();

        return new StudentsResource('STATUS_OK', 'Data Students berhasil ditampilkan', $students);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idnumber' => 'required|unique:students,idnumber',
            'fullname' => 'required',
            'gender' => 'required',
            'address' => 'required',
            'phone' => 'required|numeric|unique:students,phone',
            'emailaddress' => 'required|email|unique:students,emailaddress'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $students = Students::create([
                'idnumber' => $request->idnumber,
                'fullname' => $request->fullname,
                'gender' => $request->gender,
                'address' => $request->address,
                'phone' => $request->phone,
                'emailaddress' => $request->emailaddress,
                'photo' => ''
            ]);
            return new StudentsResource('STATUS_OK', 'Data Berhasil Ditambahkan', $students);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $students = Students::find($id);

        if ($students) {
            // return new StudentsResource('STATUS_OK', 'Data Students dengan id ' . $id . ' berhasil ditampilkan', $students);
            return response()->json([
                'status' => 'STATUS_OK',
                'payload' => $students
            ], 200);
        } else {
            return response()->json([
                'status' => 'STATUS_NOT_OK',
                'message' => 'Data with id ' . $id . ' is not found!'
            ], 400);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'idnumber' => 'required',
            'fullname' => 'required',
            'gender' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'emailaddress' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } else {
            $students = Students::find($id);

            if ($students) {
                $students->fullname = $request->fullname;
                $students->gender = $request->gender;
                $students->phone = $request->phone;
                $students->address = $request->address;
                $students->emailaddress = $request->emailaddress;
                $students->save();

                return new StudentsResource('STATUS_OK', 'Data Berhasil Diubah', $students);
            } else {
                return response()->json([
                    'message' => 'Data not found'
                ]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $students = Students::find($id);

        if ($students) {
            $students->delete();

            return new StudentsResource('STATUS_OK', 'Data berhasil dihapus', '');
        } else {
            return response()->json([
                'message' => 'Data not found'
            ]);
        }
    }

    // public function search(Request $request)
    // {
    //     $students = Students::all();
    //     if ($request->keyword) {
    //         $students->where('fullname', 'LIKE', '%' . $request->keyword . '%');
    //     }
    //     $results = $students->get();
    //     return new StudentsResource('STATUS_OK', 'Data Students berhasil ditampilkan', $results);
    // }
}
