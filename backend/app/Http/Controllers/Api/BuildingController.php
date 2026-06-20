<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Building;


class BuildingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         return response()->json(Building::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exists = Building::where(
            'building_name',
            $request->building_name
        )->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Tên tòa nhà đã tồn tại'
            ],400);
        }
        $building = Building::create([
            'building_name' => $request->building_name,
            'gender' => $request->gender,
            'description' => $request->description,
        ]);

        return response()->json($building, 201);
    
            //return response()->json($request->all());



    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Building::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {   

        $exists = Building::where(
            'building_name',
            $request->building_name
        )
        ->where('id','!=',$id)
        ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Tên tòa nhà đã tồn tại'
            ],400);
        }
        

        $building = Building::findOrFail($id);
        if (
            $building->gender != $request->gender &&
            $building->rooms()
            ->where('current_occupancy','>',0)
            ->exists()
        ) {
            return response()->json([
                'message' => 'Không thể đổi giới tính tòa nhà khi còn sinh viên ở'
            ],400);
        }

        $building->update([
            'building_name' => $request->building_name,
            'gender' => $request->gender,
            'description' => $request->description,
        ]);

        return response()->json($building);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $building = Building::findOrFail($id);

        if ($building->rooms()->count() > 0) {
            return response()->json([
                'message' => 'Tòa nhà vẫn còn phòng'
            ],400);
        }

        $building->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
