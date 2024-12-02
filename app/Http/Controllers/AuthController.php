<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Info(
 *     title="Emergency Call",
 *     version="1.0.1"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     securityScheme="bearerAuth",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    /**
* @OA\Post(
* path="/api/v1/register",
* operationId="register",
* tags={"Users"},
* summary="register",
* description="register here",
*     @OA\RequestBody(
*         @OA\JsonContent(),
*         @OA\MediaType(
*            mediaType="multipart/form-data",
*            @OA\Schema(
*               type="object",
*               required={"name","email", "password", "emergency_type","emergency_details","township","state","address","phone"},
*               @OA\Property(property="name", type="string",example="Pyae Phyo Khant"),
*               @OA\Property(property="phone", type="string",example="4384738483"),
*               @OA\Property(property="email", type="string",example="pyaephyo@gmail.com"),
*               @OA\Property(property="address", type="string",example="Kume"),
*               @OA\Property(property="emergency_type", type="string",example="Medical"),
*               @OA\Property(property="emergency_details", type="string",example="Khant Hospital"),
*               @OA\Property(property="state", type="string",example="success"),
*               @OA\Property(property="township", type="string",example="Kume"),
*               @OA\Property(property="password", type="string",example="ppk344324"),
*
*            ),
*        ),
*    ),
*      @OA\Response(
*          response=201,
*          description="Register Successfully",
*          @OA\JsonContent()
*       ),
*      @OA\Response(
*          response=200,
*          description="Register Successfully",
*          @OA\JsonContent()
*       ),
*      @OA\Response(
*          response=422,
*          description="Unprocessable Entity",
*          @OA\JsonContent()
*       ),
*      @OA\Response(response=400, description="Bad request"),
*      @OA\Response(response=404, description="Resource Not Found")
* )
*/

    public function register(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
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
            'email' => $request->email,
            'address' => $request->address,
            'emergency_type' => $request->emergency_type,
            'emergency_details' => $request->emergency_details,
            'state' => $request->state,
            'township' => $request->township,
            'password' => Hash::make($request->password)
         ]);
         return response()->json([
            'status' => True,
            'message'=> 'success',
            // 'token'=>$user->createToken("API TOKEN")->plainTextToken
        ],200);
    }

    /**
* @OA\Post(
* path="/api/v1/login",
* operationId="login",
* tags={"Users"},
* summary="login",
* description="login here",
*     @OA\RequestBody(
*         @OA\JsonContent(),
*         @OA\MediaType(
*            mediaType="multipart/form-data",
*            @OA\Schema(
*               type="object",
*               required={"email", "password"},
*               @OA\Property(property="email", type="string",example="pyaephyo@gmail.com"),
*               @OA\Property(property="password", type="string",example="ppk344324"),
*            ),
*        ),
*    ),
*      @OA\Response(
*          response=201,
*          description="Register Successfully",
*          @OA\JsonContent()
*       ),
*      @OA\Response(
*          response=200,
*          description="Register Successfully",
*          @OA\JsonContent()
*       ),
*      @OA\Response(
*          response=422,
*          description="Unprocessable Entity",
*          @OA\JsonContent()
*       ),
*      @OA\Response(response=400, description="Bad request"),
*      @OA\Response(response=404, description="Resource Not Found"),
* )
*/
    public function login (Request $request) {
        $request->validate([
            'email'=> 'required|email|exists:users,email',
            'password'=>'required'
        ]);
        $user=User::where('email',$request->email)->first();
        if(!$user || !Hash::check( $request->password,$user->password)){
            return response()->json([
                'status' => False,
                'message'=>"The provided credentials are incorrect",
            ]);
        }
        $token=$user->createToken($user->name);
        return response()->json(['status' => True,'message'=>"Login successfully",'user'=>$user,'token'=>$token->plainTextToken]);
    }

   /**
 * @OA\POST(
 *     path="/api/v1/logout",
 *     summary="Logout user",
 *     tags={"Users"},
 *     @OA\Response(
 *         response=200,
 *         description="You are logged out",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent()
 *     ),
 *     security={{"bearerAuth":{}}}
 * )
 */
    public function logout(Request $request) {
        $request->user()->tokens()->delete();
        return [
            'status' => True,
            'message'=>"You are logged out",
        ];
    }

}

