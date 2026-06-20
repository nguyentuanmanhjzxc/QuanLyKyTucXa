<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomRegistration;
use App\Models\RoomAssignment;
use App\Models\Room;
use App\Models\Student;


class RoomRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            RoomRegistration::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        // Đang ở phòng
        $assigned = RoomAssignment::where(
            'student_id',
            $request->student_id
        )
        ->where('status','DangO')
        ->exists();

        if ($assigned) {
            return response()->json([
                'message' => 'Sinh viên đang ở phòng'
            ],400);
        }
        //Đã có đơn
        $exists = RoomRegistration::where(
            'student_id',
            $request->student_id
        )
        ->whereIn('status',[
            'ChoDuyet',
            'DaDuyet'
        ])
        ->exists();
        if ($exists) {
            return response()->json([
                'message' => 'Sinh viên đã có đơn đăng ký'
            ],400);
        }
        

        //ROOM
        $room = Room::findOrFail(
            $request->room_id
        );

        //STUDENT
        $student = Student::findOrFail(
            $request->student_id
        );

        //kiểm tra sinh viên bị buộc thôi ở
        if ($student->status == 'BuocThoiO') {
            return response()->json([
                'message' => 'Sinh viên bị buộc thôi ở'
            ],400);
        }
        //kiểm tra giới tính
        if ( $student->gender !=$room->building->gender) 
        {
            return response()->json([
                'message' => 'Sai giới tính khu nhà'
            ],400);
        }

        //Chặn phòng bảo trì
        if ($room->status == 'BaoTri') {
            return response()->json([
                'message' => 'Phòng đang bảo trì'
            ], 400);
        }

        //chặn phòng đầy
        if ($room->current_occupancy >= $room->capacity) 
        {
            return response()->json([
                'message' => 'Phòng đã đầy'
            ],400);
        }

        $registration = RoomRegistration::create([
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'proof_file' => $request->proof_file,
            'registration_date' => $request->registration_date,
            'status' => $request->status,
            'approved_by' => $request->approved_by,
            'approved_at' => $request->approved_at
        ]);

        return response()->json($registration, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            RoomRegistration::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   

        $assigned = RoomAssignment::where(
            'student_id',
            $request->student_id
        )
        ->where('status','DangO')
        ->exists();

        if ($assigned) {
            return response()->json([
                'message' => 'Sinh viên đang ở phòng'
            ],400);
        }
        
        $request->validate([
            'student_id' => 'required',
            'room_id' => 'required'
        ]);
        $room = Room::findOrFail(
            $request->room_id
        );

        $student = Student::findOrFail(
            $request->student_id
        );

        // bị buộc thôi ở
        if ($student->status == 'BuocThoiO') {
            return response()->json([
                'message' => 'Sinh viên bị buộc thôi ở'
            ],400);
        }

        // sai giới tính
        if (
            $student->gender !=
            $room->building->gender
        ) {
            return response()->json([
                'message' => 'Sai giới tính khu nhà'
            ],400);
        }

        // phòng bảo trì
        if ($room->status == 'BaoTri') {
            return response()->json([
                'message' => 'Phòng đang bảo trì'
            ],400); 
        }

        $registration = RoomRegistration::findOrFail($id);

        // phòng đầy
        if (
            $registration->room_id != $request->room_id &&
            $room->current_occupancy >= $room->capacity
        ) {
            return response()->json([
                'message' => 'Phòng đã đầy'
            ],400);
        }

        $registration->update([
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'proof_file' => $request->proof_file,
            'registration_date' => $request->registration_date,
            'status' => $request->status,
            'approved_by' => $request->approved_by,
            'approved_at' => $request->approved_at
        ]);

        return response()->json($registration);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        RoomRegistration::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
