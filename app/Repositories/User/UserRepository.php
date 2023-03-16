<?php

namespace App\Repositories\User;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Repositories\User\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function index(Request $request)
    {
        $data = User::when($request->search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        })
        ->when($request->role_id, function ($query, $roleId) {
            return $query->where('role_id', $roleId);
        })
        ->orderBy('name')->get();

        return Response()->json(['data' => $data]);
    }

    public function show($id)
    {
        if (!$data = User::find($id)) {
            return Response()->json(['message' => 'User not found'], 404);
        }

        return Response()->json(['data' => $data]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return Response()->json(['message' => $validator->errors()->first()], 422);
        }

        $data = User::create($request->all());

        return Response()->json([
            'message' => 'User created successfully',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$user = User::findOrFail($id)) {
            return Response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|nullable|string|min:6',
            'role_id' => 'sometimes|integer|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return Response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user->update($request->all());
        $user->refresh();

        return Response()->json([
            'message' => 'User updated successfully',
            'data' => $user
        ]);
    }

    public function delete($id)
    {
        if (!$user = User::findOrFail($id)) {
            return Response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return Response()->json(['message' => 'User deleted successfully']);
    }
}