<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'password' => 'required|string|min:8',
            'address' => 'required|string',
            'emergency_type' => 'required|string',
            'emergency_details' => 'required',
            'state' => 'required|string',
            'township' => 'required|string',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['status' => false, 'message' => 'validation error', 'errors' => $validatedData->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            // 'email' => $request->email,
            'address' => $request->address,
            'emergency_type' => $request->emergency_type,
            'emergency_details' => $request->emergency_details,
            'state' => $request->state,
            'township' => $request->township,
            // 'password' => Hash::make($request->password)
         ]);
         return response()->json([
            'status' => True,
            'message'=> 'success',
            // 'token'=>$user->createToken("API TOKEN")->plainTextToken
        ],200);
    }

    // login
    public function login (Request $request) {
        $request->validate([
            'email'=> 'required|email|exists:users,email',
            'password'=>'required'
        ]);
        $user=User::where('email',$request->email)->first();
        if(!$user || !Hash::check( $request->password,$user->password)){
            return response()->json([
                'message'=>"The provided credentials are incorrect"
            ]);
        }
        $token=$user->createToken($user->name);
        return response()->json(['message'=>"Login successfully",'user'=>$user,'token'=>$token->plainTextToken]);
    }

    // logout
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return [
            'status' => True,
            'message'=>"You are logged out",
        ];
    }

}

