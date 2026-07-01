<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
        User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $exists = User::where(
            'username',
            $request->username
        )->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Username đã tồn tại'
            ],400);
        }
        if (!$request->password) {
            return response()->json([
                'message' => 'Password không được để trống'
            ],400);
        }
        if (
            !$request->role ||
            (
                $request->role != 'admin' &&
                $request->role != 'student'
            )
        )
        {
            return response()->json([
                'message' => 'Role không hợp lệ'
            ],400);
        }
        if (
            $request->status &&
            $request->status != 'active' &&
            $request->status != 'inactive'
        )
        {
            return response()->json([
                'message' => 'Status không hợp lệ'
            ],400);
        }

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
            'status' => $request->status ?? 'active'
        ]);

        return response()->json(
            $user,
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(
            User::findOrFail($id)
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $exists = User::where(
            'username',
            $request->username
        )
        ->where('id','!=',$id)
        ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Username đã tồn tại'
            ],400);
        }
        if (
            $request->role &&
            $request->role != 'admin' &&
            $request->role != 'student'
        )
        {
            return response()->json([
                'message' => 'Role không hợp lệ'
            ],400);
        }
        

        $user = User::findOrFail($id);
        if (
            $user->role == 'admin' &&
            $request->role == 'student'
        ) {
            return response()->json([
                'message' => 'Không được đổi quyền admin'
            ],400);
        }
        $data = [];

        if ($request->username) {
            $data['username'] = $request->username;
        }

        if ($request->role) {
            $data['role'] = $request->role;
        }

        if ($request->status) {
            $data['status'] = $request->status;
        }

        if ($request->password) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->role == 'admin') {
            return response()->json([
                'message' => 'Không được xóa tài khoản admin'
            ],400);
        }

        $user->delete();

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }
}
