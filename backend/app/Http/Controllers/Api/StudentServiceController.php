<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentService;
use App\Models\Student;
use App\Models\Service;

class StudentServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            StudentService::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            
        $student = Student::findOrFail(
            $request->student_id
        );
        $assigned = $student->roomAssignments()
            ->where('status','DangO')
            ->exists();

        if (!$assigned) {
            return response()->json([
                'message' => 'Sinh viên chưa ở KTX'
            ],400);
        }

        if ($student->status == 'BuocThoiO') {
            return response()->json([
                'message' => 'Sinh viên không đủ điều kiện sử dụng dịch vụ'
            ],400);
        }

        $service = Service::findOrFail(
            $request->service_id
        );

        if (!$service->status) {
            return response()->json([
                'message' => 'Dịch vụ đang ngừng hoạt động'
            ], 400);
        }

        $exists = StudentService::where(
            'student_id',
            $request->student_id
        )->where('service_id', $request->service_id)
         ->where('status', 'Active' )
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Sinh viên đã đăng ký dịch vụ này'
            ], 400);
        }

        if ($request->end_date && $request->end_date < $request->start_date) {
            return response()->json([
                'message' => 'Ngày kết thúc không hợp lệ'
            ], 400);
        }
        

        $studentService = StudentService::create([
            'student_id' => $request->student_id,   
            'service_id' => $request->service_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'Active'
        ]);

        return response()->json($studentService, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            StudentService::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   
        $studentService =StudentService::findOrFail($id);

        $student = Student::findOrFail($studentService->student_id);
        $service = Service::findOrFail($studentService->service_id);
        

        $assigned = $student->roomAssignments()
            ->where('status','DangO')
            ->exists();

        if (!$assigned) {
            return response()->json([
                'message' => 'Sinh viên chưa ở KTX'
            ],400);
        }
        
        if (!$service->status && $request->status == 'Active') {
            return response()->json([
                'message' => 'Dịch vụ đang ngừng hoạt động'
            ],400);
        }

        if ($student->status == 'BuocThoiO') {
            return response()->json([
                'message' => 'Sinh viên không đủ điều kiện sử dụng dịch vụ'
            ],400);
        }

        if ($studentService->student_id != $request->student_id)
        {
            return response()->json([
                'message' =>
                'Không được đổi sinh viên'
            ],400);
        }

        if ($studentService->service_id != $request->service_id) 
        {
            return response()->json([
                'message' =>
                'Không được đổi dịch vụ'
            ],400);
        }

        if ($request->end_date && $request->end_date < $request->start_date
        ) {
            return response()->json([
                'message' =>
                'Ngày kết thúc không hợp lệ'
            ],400);
        }
        if (
            $request->status != 'Active' &&
            $request->status != 'Inactive'
        ) {
            return response()->json([
                'message' => 'Trạng thái không hợp lệ'
            ],400);
        }


        $studentService->update([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status
        ]);

        return response()->json(
            $studentService
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $studentService = StudentService::findOrFail($id);


        if ($studentService->status == 'Inactive') {
            return response()->json([
                'message' => 'Dịch vụ đã được hủy trước đó'
            ],400);
        }
        
        $studentService->update([
            'status' => 'Inactive'
        ]);

        return response()->json([
            'message' =>
            'Dịch vụ đã được hủy'
        ]);
    }
}
