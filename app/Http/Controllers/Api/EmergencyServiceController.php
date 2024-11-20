<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\EmergencyService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;


class EmergencyServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service = EmergencyService::all();

        return response()->json([
            'status' => true,
            'message' => 'Showing all service',
            'data' => $service,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = $this->getServiceValidationCheckUp($request);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->messages(),
            ], 422);
        }

        $data = $this->getServiceStore($request);

        EmergencyService::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Data Upload is success',
        ], 200);
    }

    public function getServiceByType($service_type)
    {
        $service = EmergencyService::where('service_type', $service_type)->get();

        if ($service->isNotEmpty()) {
            return response()->json([
                'status' => true,
                'data' => $service,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'This Service Type is not found', // Corrected typo
        ], 200);
    }

    public function getServiceById($id)
    {
        $service = EmergencyService::where('id', $id)->first();

        if ($service) {
            return response()->json([
                'status' => true,
                'data' => $service,
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'This Servie Type is not Found',
        ], 200);
    }

    // private function

    private function getServiceValidationCheckUp($request)
    {
        return Validator::make($request->all(), [
            'service_type' => 'required',
            'service_name' => 'required',
            'phone_number' => 'required',
            'location' => 'required',
            'state' => 'required',
            'township' => 'required',
        ]);
    }

    private function getServiceStore($request)
    {
        return [
            'service_type' => $request->service_type,
            'service_name' => $request->service_name,
            'phone_number' => $request->phone_number,
            'location' => $request->location,
            'availability' => '1',
            'state' => $request->state,
            'township' => $request->township,
        ];
    }
}
