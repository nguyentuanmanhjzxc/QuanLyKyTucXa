<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\Student;

class ViolationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Violation::with('student')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $student = Student::find($request->student_id);

        if(!$student){
            return response()->json([
                'message'=>'Sinh viên không tồn tại'
            ],400);
        }

        if(!$request->violation_type){
            return response()->json([
                'message'=>'Violation type là bắt buộc'
            ],400);
        }

        if(
            $request->violation_date &&
            !strtotime($request->violation_date)
        ){
            return response()->json([
                'message'=>'Ngày không hợp lệ'
            ],400);
        }

        if(
            $request->penalty &&
            $request->penalty!='NhacNho' &&
            $request->penalty!='CanhCao' &&
            $request->penalty!='BuocThoiO'
        ){
            return response()->json([
                'message'=>'Vi phạm không hợp lệ'
            ],400);
        }

        $violation = Violation::create([
            'student_id'=>$request->student_id,
            'violation_type'=>$request->violation_type,
            'description'=>$request->description,
            'penalty'=>$request->penalty,
            'violation_date'=>$request->violation_date
        ]);

        return response()->json($violation,201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Violation::with('student')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
                $violation = Violation::findOrFail($id);

        if($request->has('student_id')){

            $student = Student::find($request->student_id);

            if(!$student){
                return response()->json([
                    'message'=>'Sinh viên không tồn tại'
                ],400);
            }

            $violation->student_id = $request->student_id;
        }

        if($request->has('violation_type')){

            if(!$request->violation_type){
                return response()->json([
                    'message'=>'Violation type không được rỗng'
                ],400);
            }

            $violation->violation_type = $request->violation_type;
        }

        if(
            $request->violation_date &&
            !strtotime($request->violation_date)
        ){
            return response()->json([
                'message'=>'Ngày không hợp lệ'
            ],400);
        }

        if($request->has('description')){
            $violation->description = $request->description;
        }

        if($request->has('penalty')){

            if(
                $request->penalty!='NhacNho' &&
                $request->penalty!='CanhCao' &&
                $request->penalty!='BuocThoiO'
            ){
                return response()->json([
                    'message'=>'Vi phạm không hợp lệ'
                ],400);
            }

            $violation->penalty = $request->penalty;
        }

        if($request->has('violation_date')){
            $violation->violation_date = $request->violation_date;
        }

        $violation->save();

        return response()->json($violation);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $violation = Violation::findOrFail($id);
        $violation->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
