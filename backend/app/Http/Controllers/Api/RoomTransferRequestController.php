<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomTransferRequest;
use App\Models\RoomAssignment;
use App\Models\Student;
use App\Models\Room;

class RoomTransferRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            RoomTransferRequest::all()
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

        $oldRoom = Room::findOrFail(
            $request->old_room_id
        );

        $newRoom = Room::findOrFail(
            $request->new_room_id
        );

        if ($oldRoom->id == $newRoom->id) {
            return response()->json([
                'message' => 'Phòng mới phải khác phòng cũ'
            ],400);
        }

        $assignment = RoomAssignment::where(
            'student_id',
            $student->id
        )
        ->where('room_id',$oldRoom->id)
        ->where('status','DangO')
        ->exists();

        if (!$assignment) {
            return response()->json([
                'message' => 'Sinh viên không ở phòng này'
            ],400);
        }

        $pending = RoomTransferRequest::where(
            'student_id',
            $student->id
        )
        ->where('status','ChoDuyet')
        ->exists();

        if ($pending) {
            return response()->json([
                'message' => 'Đã có yêu cầu chuyển phòng đang chờ duyệt'
            ],400);
        }

        if (
            $newRoom->current_occupancy >=
            $newRoom->capacity
        ) {
            return response()->json([
                'message' => 'Phòng mới đã đầy'
            ],400);
        }

        if ($newRoom->status == 'BaoTri') {
            return response()->json([
                'message' => 'Phòng mới đang bảo trì'
            ],400);
        }

        $building = $newRoom->building;

        if ($student->gender != $building->gender) {
            return response()->json([
                'message' => 'Giới tính không phù hợp với tòa nhà'
            ],400);
        }

        $transfer = RoomTransferRequest::create([
            'student_id' => $request->student_id,
            'old_room_id' => $request->old_room_id,
            'new_room_id' => $request->new_room_id,
            'reason' => $request->reason,
            'status' => 'ChoDuyet'
        ]);

        return response()->json(
            $transfer,
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            RoomTransferRequest::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $transfer =
            RoomTransferRequest::findOrFail($id);

        if ($transfer->status != 'ChoDuyet') {
            return response()->json([
                'message' => 'Yêu cầu đã được xử lý'
            ],400);
        }

        if (
            $request->status != 'DaDuyet' &&
            $request->status != 'TuChoi'
        ) {
            return response()->json([
                'message' => 'Trạng thái không hợp lệ'
            ],400);
        }

        if ($request->status == 'DaDuyet')
        {
            $oldRoom = Room::findOrFail(
                $transfer->old_room_id
            );

            $newRoom = Room::findOrFail(
                $transfer->new_room_id
            );

            if (
                $newRoom->current_occupancy >=
                $newRoom->capacity
            ) {
                return response()->json([
                    'message' => 'Phòng mới đã đầy'
                ],400);
            }

            $oldRoom->decrement(
                'current_occupancy'
            );

            $newRoom->increment(
                'current_occupancy'
            );
            
              $student = Student::findOrFail(
                $transfer->student_id
            );

            $building = $newRoom->building;

            if (
                $student->gender !=
                $building->gender
            )
            {
                return response()->json([
                    'message' => 'Sai giới tính khu nhà'
                ],400);
            }

            RoomAssignment::where(
                'student_id',
                $transfer->student_id
            )
            ->where(
                'status',
                'DangO'
            )
            ->update([
                'room_id' => $newRoom->id
            ]);
            
          
        }

        $transfer->update([
            'status' => $request->status,
            'approved_at' => now()
        ]);

        return response()->json(
            $transfer
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $transfer =
            RoomTransferRequest::findOrFail($id);

        if ($transfer->status == 'DaDuyet') {
            return response()->json([
                'message' =>
                'Không được xóa yêu cầu đã duyệt'
            ],400);
        }

        $transfer->delete();

        return response()->json([
            'message' =>
            'Deleted successfully'
        ]);
    
    }
}
