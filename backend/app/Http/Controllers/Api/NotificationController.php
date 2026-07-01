<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\User;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Notification::with('creator')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$request->created_by) {
            return response()->json([
                'message'=>'created_by là bắt buộc'
            ],400);
        }

        $user = User::find($request->created_by);

        if (!$user) {
            return response()->json([
                'message'=>'User không tồn tại'
            ],400);
        }

        if (!$request->title) {
            return response()->json([
                'message'=>'Title là bắt buộc'
            ],400);
        }

        if (!$request->content) {
            return response()->json([
                'message'=>'Content là bắt buộc'
            ],400);
        }
        
        $notification = Notification::create([
            'title'=>$request->title,
            'content'=>$request->content,
            'created_by'=>$request->created_by
        ]);

        return response()->json($notification,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            Notification::with('creator')->findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $notification = Notification::findOrFail($id);

        if($request->has('created_by')){

            $user = User::find($request->created_by);

            if(!$user){
                return response()->json([
                    'message'=>'User không tồn tại'
                ],400);
            }

            $notification->created_by = $request->created_by;
        }

        if($request->has('title')){

            if(!$request->title){
                return response()->json([
                    'message'=>'Title không được rỗng'
                ],400);
            }

            $notification->title = $request->title;
        }

        if($request->has('content')){

            if(!$request->content){
                return response()->json([
                    'message'=>'Content không được rỗng'
                ],400);
            }

            $notification->content = $request->content;
        }

        $notification->save();

        return response()->json($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         $notification = Notification::findOrFail($id);

        $notification->delete();

        return response()->json([
            'message'=>'Delete successfully'
        ]);
    }
}
