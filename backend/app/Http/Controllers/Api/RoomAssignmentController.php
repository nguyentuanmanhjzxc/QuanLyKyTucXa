<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomAssignment;
use App\Models\Room;
use App\Models\Student;

class RoomAssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            RoomAssignment::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Chặn sinh viên ở 2 phòng cùng lúc
        $exists = RoomAssignment::where(
            'student_id',
            $request->student_id
        )
        ->where('status', 'DangO')
        ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Sinh viên đã có phòng'
            ], 400);
        }

        $room = Room::findOrFail($request->room_id);

        $student = Student::findOrFail(
            $request->student_id
        );

        //Chặn sinh viên chưa được duyệt đăng ký KTX
        if ($student->status == 'ChoDuyet' ||$student->status == 'BuocThoiO') 
        {
            return response()->json([
                'message' => 'Sinh viên không đủ điều kiện ở KTX'
            ],400);
        }

        // Kiểm tra giới tính sinh viên với khu nhà
        if (    
            $student->gender !=
            $room->building->gender
        ) {
            return response()->json([
                'message' => 'Sai giới tính khu nhà'
            ], 400);
        }

        // Phòng bảo trì
    
        if ($room->status == 'BaoTri') {
        return response()->json([
            'message' => 'Phòng đang bảo trì'
        ], 400);
    }

        // Chặn phòng đầy

        if ($room->current_occupancy >= $room->capacity) {
            return response()->json([
                'message' => 'Phòng đã đầy'
            ], 400);
        }

        $assignment = RoomAssignment::create([
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status' => $request->status
        ]);

        // Tăng số người trong phòng
        if ($request->status == 'DangO') {
             $room->increment('current_occupancy');
        }
        
        return response()->json($assignment, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            RoomAssignment::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $assignment = RoomAssignment::findOrFail($id);

        $oldRoomId = $assignment->room_id;
        $oldStatus = $assignment->status;
        if (
            $assignment->student_id !=
            $request->student_id
        ) {
            return response()->json([
                'message' => 'Không được đổi sinh viên'
            ],400);
        }
        // Nếu đổi phòng
        if ( $oldRoomId != $request->room_id && $oldStatus == 'DangO') {

            $newRoom = Room::findOrFail($request->room_id);

            $student = Student::findOrFail(
                $request->student_id
            );

            // Kiểm tra giới tính
            if (
                $student->gender !=
                $newRoom->building->gender
            ) {
                return response()->json([
                    'message' => 'Sai giới tính khu nhà'
                ],400);
            }

            // Phòng bảo trì
            if ($newRoom->status == 'BaoTri') {
                return response()->json([
                    'message' => 'Phòng đang bảo trì'
                ],400);
            }

            // Phòng đầy
            if (
                $newRoom->current_occupancy >=
                $newRoom->capacity
            ) {
                return response()->json([
                    'message' => 'Phòng đã đầy'
                ],400);
            }

            // Giảm phòng cũ
            $oldRoom = Room::find($oldRoomId);

            if (
                $oldRoom &&
                $oldRoom->current_occupancy > 0
            ) {
                $oldRoom->decrement(
                    'current_occupancy'
                );
            }

            // Tăng phòng mới
            $newRoom->increment(
                'current_occupancy'
            );
        }

        $assignment->update([
            'student_id' => $request->student_id,
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status' => $request->status
        ]);

        // Trả phòng
        if (
            $oldStatus == 'DangO' &&
            $request->status == 'DaTraPhong'
        ) {
            $room = Room::find($assignment->room_id);

            if (
                $room &&
                $room->current_occupancy > 0
            ) {
                $room->decrement(
                    'current_occupancy'
                );
            }
        }

        // Nhận lại phòng
        if (
            $oldStatus == 'DaTraPhong' &&
            $request->status == 'DangO'
        ) {
            $room = Room::find($assignment->room_id);

            if (
                $room->current_occupancy >=
                $room->capacity
            ) {
                return response()->json([
                    'message' => 'Phòng đã đầy'
                ],400);
            }
            else {
                $room->increment('current_occupancy');

            }
        }

        return response()->json($assignment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $assignment = RoomAssignment::findOrFail($id);

        $room = Room::find($assignment->room_id);

        if (
            $room &&
            $assignment->status == 'DangO' &&
            $room->current_occupancy > 0
        ) {
            $room->decrement(
                'current_occupancy'
            );
        }

        $assignment->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
