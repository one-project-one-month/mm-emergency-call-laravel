<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
* @OA\Get(
* path="/api/v1/users",
* operationId="Show User",
* tags={"Users"},
* summary="Users",
* description="Users here",
*      @OA\Response(
*          response=201,
*          description="Successfully",
*          @OA\JsonContent()
*       ),
*      @OA\Response(
*          response=200,
*          description="Successfully",
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
    public function index()
    {
        $user = User::all();
        return response()->json(['status' => True,'data' => $user]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    /**
* @OA\Get(
* path="/api/v1/users/{id}",
* operationId="users",
* tags={"Users"},
* summary="userss",
* description="users here",
*     @OA\RequestBody(
*         @OA\JsonContent(),
*         @OA\MediaType(
*            mediaType="multipart/form-data",
*        ),
*    ),
*      @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
*      @OA\Response(
*          response=201,
*          description="Search Successfully",
*          @OA\JsonContent()
*       ),
*      @OA\Response(
*          response=200,
*          description="Search Successfully",
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
    public function show(string $id)
    {
        $searchId = User::where('id', $id)->first();
        if (isset($searchId)) {
            $data = User::where('id', $id)->first();
            return response()->json(['status' => True, 'data' => $data], 200);
        }
        return response()->json(['status' => False, 'message' => 'Try Again'], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
 * @OA\Patch(
 *     path="/api/v1/users/{id}",
 *     operationId="UpdateUsers",
 *     tags={"Users"},
 *     summary="Update User",
 *     description="Update User details",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Agent ID",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             required={"name","email", "password", "emergency_type","emergency_details","township","state","address","phone"},
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
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User updated successfully",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found",
 *         @OA\JsonContent()
 *     ),
 *     security={{"bearerAuth":{}}}
 * )
 */

    public function update(Request $request, string $id)
    {
        $user = User::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'status' => False,
                'message' => "User is not found",
            ], 400);
        }

        $validatedData = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users,phone,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
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
        $data = User::where('id', $id)->update($request->all());
        return response()->json(['status' => True,'message' => "user updated successfully",], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
 * @OA\Delete(
 *     path="/api/v1/users/{id}",
 *     operationId="DeleteUser",
 *     tags={"Users"},
 *     summary="Delete Users",
 *     description="Delete User details",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="User ID",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User deleted successfully",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Unprocessable Entity",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request",
 *         @OA\JsonContent()
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Resource not found",
 *         @OA\JsonContent()
 *     ),
 *     security={{"bearerAuth":{}}}
 * )
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

