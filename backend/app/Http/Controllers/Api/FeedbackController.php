<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Feedback;
use App\Models\Student;


class FeedbackController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()-> json(
            Feedback::with('student')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->student_id) {
            return response()->json([
                'message' => 'student_id không được để trống'
            ],400);
        }

        $student = Student::find($request->student_id);
        if (!$student) {
            return response()->json([
                'message' => 'Sinh viên không tồn tại'
            ], 400);
        }

        if ($request->has('status') && $request->status != 'Moi' &&$request->status != 'DaXuLy'
        ){
            return response()->json([
                'message'=>'Status không hợp lệ'
            ],400);
        }

        if (!$request->title) {
            return response()->json([
                'message' => 'title không được để trống'
            ], 400);
        }

        if (!$request->content) {
            return response()->json([
                'message' => 'content không được để trống'
            ], 400);
        }


         $feedback = Feedback::create([
            'student_id' => $request->student_id,
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status ?? 'Moi'
        ]);

         return response()->json($feedback, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Feedback::with('student')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $feedback = Feedback::findOrFail($id);

        if ($request->has('student_id')) {
            if (!$request->student_id) {
                return response()->json([
                    'message' => 'ID không được để trống'
                ], 400);
            }

            $student = Student::find($request->student_id);
            if (!$student) {
                return response()->json([
                    'message' => 'Sinh viên không tồn tại'
                ], 400);
            }

            $feedback->student_id = $request->student_id;
        }

        if ($request->has('title')) {
            if (!$request->title) {
                return response()->json([
                    'message' => 'title không được để trống'
                ], 400);
            }

            $feedback->title = $request->title;
        }

        if ($request->has('content')) {
            if (!$request->content) {
                return response()->json([
                    'message' => 'content không được để trống'
                ], 400);
            }

            $feedback->content = $request->content;
        }

        if ($request->has('status')) {

            if (
                $request->status != 'Moi' &&
                $request->status != 'DaXuLy'
            ) {
                return response()->json([
                    'message' => 'Status không hợp lệ'
                ],400);
            }

            $feedback->status = $request->status;
        }


        $feedback->save();

        return response()->json($feedback);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
