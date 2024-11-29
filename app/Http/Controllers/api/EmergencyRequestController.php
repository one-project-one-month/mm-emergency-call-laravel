<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\EmergencyRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\EmergencyService;
use Illuminate\Support\Facades\Validator;


class EmergencyRequestController extends Controller
{
    public function index(Request $request)
    {

        $service_request = EmergencyRequest::query();
        if ($request->query('status')) {
            $service_request->where('status', $request->query('status'));
        }
        $request = $service_request->paginate(5);
        return response()->json(['status' => true, 'message' => $request], 200);
    }

    public function show($id)
    {
        $service_request = EmergencyRequest::find($id);
        if ($service_request) {
            return response()->json([
                'status' => true,
                'message' => $service_request
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Emergency Service Request'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userId' => 'required',
            'serviceId' => 'required',
            'providerId' => 'required',
            // 'requestTime' => 'required',
            // 'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 403);
        };

        $emergencyRequest = new EmergencyRequest();
        $emergencyRequest->user_id = $request->userId;
        $emergencyRequest->service_id = $request->serviceId;
        $emergencyRequest->provider_id = $request->providerId;
        // $emergencyRequest->request_time = Carbon::parse($request->requestTime)->format('Y-m-d H:i:s');
        $emergencyRequest->request_time = Carbon::now();
        $emergencyRequest->status = 'pending';
        $emergencyRequest->response_time = null;
        $emergencyRequest->notes = $request->notes ?? null;
        $emergencyRequest->state = $request->state ?? null;
        $emergencyRequest->township = $request->township ?? null;

        $save = $emergencyRequest->save();
        if ($save) {
            return response()->json([
                'status' => true,
                'message' => 'success',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'create request unsuccess',
            ], 203);
        }
    }

    public function update($id, Request $request)
    {
        $emergencyRequest = EmergencyRequest::find($id);
        if ($emergencyRequest['status'] == 'pending') {
            $emergencyRequest->user_id = $request->UserId ?? $emergencyRequest->user_id;
            $emergencyRequest->service_id = $request->ServiceId ?? $emergencyRequest->service_id;
            $emergencyRequest->provider_id = $request->ProviderId ?? $emergencyRequest->provider_id;
            $emergencyRequest->request_time = $request->RequestTime ?? $emergencyRequest->request_time;
            $emergencyRequest->status = $request->Status ?? $emergencyRequest->status;
            $emergencyRequest->response_time = $request->ResponseTime ?? $emergencyRequest->response_time;
            $emergencyRequest->notes = $request->Notes ?? $emergencyRequest->notes;
            $emergencyRequest->state = $request->State ?? $emergencyRequest->state;
            $emergencyRequest->township = $request->Township ?? $emergencyRequest->township;
            logger($emergencyRequest);
            $save = $emergencyRequest->save();
            if ($save) {
                return response()->json([
                    'status' => true,
                    'message' => $emergencyRequest,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'update unsuccess',
                ], 203);
            }
        } elseif ($emergencyRequest['status'] == 'approved' || $emergencyRequest['status'] == 'rejected') {
            return response()->json([
                'status' => false,
                'message' => 'Service being approved. Unavailable to change',
            ], 200);
        }
    }

    // public function destroy($id){
    //     $emergencyRequest = EmergencyRequest::find($id);
    //     if($emergencyRequest){
    //         $emergencyRequest->delete();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'emergency service request delete successful',
    //         ],200);
    //     }else{
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'No emergency service request',
    //         ],200);
    //     }
    // }

    public function getService($service_id, Request $request)
    {
        // $service = EmergencyRequest::with('emergency_services')->where('service_id',$service_id)->get();
        // $service = EmergencyService::with(['emergency_request' => function($query) {
        //                                     $query->where('status','pending');
        //                             }])
        //                             ->where('id',$service_id)
        //                             ->get();
        $service = EmergencyService::with('emergency_request');
        $status = $request->query('status');
        if ($status) {
            $service->whereHas('emergency_request', function ($query) use ($status,$service_id) {
                $query->where('id', $service_id)->where('status', $status);
            });

        }else{
            $service->where('id', $service_id)->get();
        }
        $service = $service->get();
        if($service){
            return response()->json([
                'status' => true,
                'message' => $service,
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'No service is match',
            ], 200);
        }
    }

    public function getProvider($provider_id)
    {
        $provider = EmergencyRequest::with('service_providers')->where('provider_id', $provider_id)->get();
        if ($provider) {
            return response()->json([
                'status' => true,
                'message' => $provider,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No emergency provider match',
            ], 200);
        }
    }

    public function updateServiceStatus(Request $request)
    {
        $service_request = $request->request_id;
        $service = EmergencyRequest::find($service_request);
        $service->status = $request->status;
        $service->response_time = Carbon::now();
        $service->save();

        return response()->json([
            'status' => true,
            'message' => $service,
        ], 200);
    }
}
