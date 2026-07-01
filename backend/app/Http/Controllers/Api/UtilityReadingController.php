<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UtilityReading;
use App\Models\Invoice;

class UtilityReadingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return response() -> json(
            UtilityReading::all()
       );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exist = UtilityReading::where('room_id',$request->room_id)
        ->where('month',$request->month)
        ->where('year',$request->year)
        ->exists();
        
        if($exist)
        {
            return response() -> json(['message' =>'Đã có chỉ số tháng này'],400);
        }
        if($request->month < 1 || $request->month > 12)
        {
            return response() -> json(['message'=> 'Tháng không hợp lệ'],400);
        }
        if($request->electric_new < $request->electric_old)
        {
            return response() -> json(['message' => 'Chỉ số điện không hợp lệ'],400);
        }
        if($request->water_new < $request->water_old)
        {
            return response() -> json(['message' => 'Chỉ số nước không hợp lệ'],400);
        }
        if ($request->year < 2020) {
            return response()->json(['message' => 'Năm không hợp lệ'],400);
        }

         $reading = UtilityReading::create([
        'room_id' => $request->room_id,
        'month' => $request->month,
        'year' => $request->year,
        'electric_old' => $request->electric_old,
        'electric_new' => $request->electric_new,
        'water_old' => $request->water_old,
        'water_new' => $request->water_new
    ]);

        return response()->json(
        $reading,
        201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response() -> json(
            UtilityReading::findOrFail($id) 
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $reading = UtilityReading::findOrFail($id);

        if($reading->room_id != $request->room_id)
        {
            return response()->json([ 'message' => 'Không được đổi phòng'],400);
        }
        if($reading->month != $request->month)
        {
            return response()->json(['message' => 'Không được đổi tháng'],400);
        }
        if($reading->year != $request->year)
        {
            return response() ->json(['message' => 'Không được đổi năm'],400);
        }
        if ($request->electric_new < $request->electric_old)
        {
            return response()->json(['message' => 'Chỉ số điện không hợp lệ'],400);
        }

        if ($request->water_new < $request->water_old) 
        {
            return response()->json(['message' => 'Chỉ số nước không hợp lệ'],400);
        }

        $invoiceExists = Invoice::where('reading_id',$reading->id)->exists();

        if ($invoiceExists) 
        {
            return response()->json(['message' => 'Đã phát sinh hóa đơn, không được sửa'],400);
        }
        
        $reading->update([
            'electric_old' => $request->electric_old,
            'electric_new' => $request->electric_new,
            'water_old' => $request->water_old,
            'water_new' => $request->water_new
        ]);

        return response()->json(
            $reading
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $reading = UtilityReading::findOrFail($id);

        $exists = Invoice::where('reading_id',$reading->id)->exists();

        if ($exists) {
            return response()->json(['message' => 'Đã phát sinh hóa đơn'],400);
        }

        $reading->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
