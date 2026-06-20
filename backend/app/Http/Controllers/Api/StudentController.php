<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response() -> json(
            Student::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        //MSSV
        $exists = Student::where(
            'student_code',
            $request->student_code
        )->exists();
        if ($exists) {
            return response()->json([
                'message' => 'MSSV đã tồn tại'
            ],400);
        }

        //PHONE
        $phoneExists = Student::where(
            'phone',
            $request->phone
        )->exists();
        if ($phoneExists) {
            return response()->json([
                'message' => 'Số điện thoại đã tồn tại'
            ],400);
        }

        //EMAIL
        $emailExists = Student::where(
            'email',
            $request->email
        )->exists();

        if ($emailExists) {
            return response()->json([
                'message' => 'Email đã tồn tại'
            ],400);
        }

        $student = Student::create($request->all());

        return response()->json($student, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Student::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //MSSV  
        $exists = Student::where(
            'student_code',
            $request->student_code
        )
        ->where('id','!=',$id)
        ->exists();
        if ($exists) {
            return response()->json([
                'message' => 'MSSV đã tồn tại'
            ],400);
        }

        //PHONE
        $phoneExists = Student::where(
            'phone',
            $request->phone
        )->where('id','!=',$id)
        ->exists();
        if ($phoneExists) {
            return response()->json([
                'message' => 'Số điện thoại đã tồn tại'
            ],400);
        }

        //EMAIL
        $emailExists = Student::where(
            'email',
            $request->email
        )->where('id','!=',$id)
        ->exists();
        if ($emailExists) {
            return response()->json([
                'message' => 'Email đã tồn tại'
            ],400);
        }

        $student = Student::findOrFail($id);

        if (
            $student->roomAssignments()
            ->where('status','DangO')
            ->exists()
            &&
            $student->gender != $request->gender
        ) {
            return response()->json([
                'message' => 'Không thể đổi giới tính khi đang ở KTX'
            ],400);
        }

        $student->update($request->all());

        return response()->json($student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::findOrFail($id);
        if (
            $student->roomAssignments()
            ->where('status','DangO')
            ->exists()
        ) {
            return response()->json([
                'message' => 'Sinh viên đang ở KTX'
            ],400);
        }
        $student->delete();

        return response()->json([
            'message' => 'Delete success'
        ]);
    }
}
