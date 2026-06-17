<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;


class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Room::with('building')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $room = Room::create([
            'building_id' => $request->building_id,
            'room_code' => $request->room_code,
            'capacity' => $request->capacity,
            'current_occupancy' => $request->current_occupancy,
            'price' => $request->price,
            'status' => $request->status
        ]);

        return response()->json($room,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            return response()->json(
            Room::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        $room->update([
            'building_id' => $request->building_id,
            'room_code' => $request->room_code,
            'capacity' => $request->capacity,
            'current_occupancy' => $request->current_occupancy,
            'price' => $request->price,
            'status' => $request->status,
        ]);

        return response()->json($room);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Room::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
