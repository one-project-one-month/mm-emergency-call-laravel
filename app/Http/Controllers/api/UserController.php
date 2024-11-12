<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return response()->json(['data' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $searchId = User::where('id', $id)->first();
        if (isset($searchId)) {
            $data = User::where('id', $id)->first();
            return response()->json($data, 200);
        }
        return response()->json(['status' => False, 'message' => 'Try Again'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'message' => "User is not found",
            ], 400);
        }

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'required|string|min:8',
            'address' => 'required|string',
            'emergencyType' => 'required|string',
            'emergencyDetails' => 'required',
            'state' => 'required|string',
            'township' => 'required|string',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['status' => false, 'message' => 'validation error', 'errors' => $validatedData->errors()], 422);
        }
        $data = User::where('id', $id)->update($request->all());
        return response()->json(['message' => "user updated successfully",], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $searchId = User::where('id', $id)->first();
        if (isset($searchId)) {
            $data = User::where('id', $id)->delete();
            return response()->json(['status' => True, 'data' => $data], 200);
        }
        return response()->json(['status' => False, 'message' => 'Try Again'], 200);
    }
}
