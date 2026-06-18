<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoomRegistration;


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
                $registration = RoomRegistration::findOrFail($id);

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
