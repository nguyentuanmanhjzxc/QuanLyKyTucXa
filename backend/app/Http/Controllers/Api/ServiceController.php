<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;


class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Service::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exists = Service::where(
            'service_name',
            $request->service_name
        )->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Tên dịch vụ đã tồn tại'
            ],400);
        }
        
        if ($request->price <= 0) {
            return response()->json([
                'message' => 'Giá dịch vụ không hợp lệ'
            ],400);
        }

        $service = Service::create([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'status' => $request->status
        ]);

        return response()->json($service, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Service::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $service = Service::findOrFail($id);

        $exists = Service::where(
            'service_name',
            $request->service_name
        )
        ->where('id','!=',$id)
        ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Tên dịch vụ đã tồn tại'
            ],400);
        }

        if ($request->price <= 0) {
            return response()->json([
                'message' => 'Giá dịch vụ không hợp lệ'
            ],400);
        }

        
        $service->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
            'status' => $request->status
        ]);

        return response()->json($service);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);

        if ($service->studentServices()->exists()) {
            return response()->json([
                'message' => 'Dịch vụ đang được sử dụng'
            ],400);
        }

        $service->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
