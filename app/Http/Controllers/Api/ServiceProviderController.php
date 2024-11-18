<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ServiceProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ServiceProvider::query();

        if ($request->has('contact_number')) {
            $query->where('contact_number', 'like', '%' . $request->contact_number . '%');
        }

        $Companies = $query->paginate(5);
        return response()->json(['status' => True,$Companies]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'provider_name' => 'required|string|max:255',
            'service_id' => 'required|exists:emergency_services,id',
            'contact_number' => 'required',
            'availability' => 'required',
            'state' => 'required|string',
            'township' => 'required|string',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['status' => false, 'message' => 'validation error', 'errors' => $validatedData->errors()], 422);
        }

        $ServiceProvider = ServiceProvider::create([
            'provider_name' => $request->provider_name,
            'service_id' => $request->service_id,
            'contact_number' => $request->contact_number,
            'availability' => $request->availability,
            'state' => $request->state,
            'township' => $request->township
        ]);
        return response()->json(['status' => True,'message'=> $ServiceProvider],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $searchId = ServiceProvider::where('id',$id)->first();
        if(isset($searchId)) {
            $data = ServiceProvider::where('id',$id)->first();
            return response()->json(['status' => True,$data, 200]);
        }
        return response()->json(['status' => False ,'message' => 'Try Again'],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = ServiceProvider::where('id', $id)->first();
        if (!$user) {
            return response()->json([
                'status' => False,
                'message' => "ID is not found",
            ], 400);
        }

        $validatedData = Validator::make($request->all(), [
            'provider_name' => 'required|string|max:255',
            'service_id' => 'required',
            'contact_number' => 'required',
            'availability' => 'required',
            'state' => 'required|string',
            'township' => 'required|string',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['status' => false, 'message' => 'validation error', 'errors' => $validatedData->errors()], 422);
        }
        $data = ServiceProvider::where('id', $id)->update($request->all());
        return response()->json(['status' => True,'message' => "updated successfully",], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $searchId = ServiceProvider::where('id', $id)->first();
        if (isset($searchId)) {
            $data = ServiceProvider::where('id', $id)->delete();
            return response()->json(['status' => True, 'data' => $searchId], 200);
        }
        return response()->json(['status' => False, 'message' => 'Try Again'], 200);
    }

    // searchServiceId
    public function ServiceID($service_id){
        $service = ServiceProvider::with('emergency_services')->where('service_id',$service_id)->get();
        if($service){
           return response()->json([
                'status' => true,
                'message' => $service,
            ],200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No emergency service match',
            ],200);
        }
    }

    
}
