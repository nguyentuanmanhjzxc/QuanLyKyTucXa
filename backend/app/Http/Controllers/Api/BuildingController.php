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
        $building = Building::findOrFail($id);

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
        Building::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
