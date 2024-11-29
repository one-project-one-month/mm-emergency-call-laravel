<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\EmergencyService;
use App\Models\ServiceType;
use Illuminate\Support\Facades\Validator;


class ServiceTypeController extends Controller
{
    public function index()
    {

        $service_type = ServiceType::all();
        if($service_type){
            return response()->json(['status' => true, 'message' => $service_type], 200);
        }else{
            return response()->json(['status' => true, 'message' => 'no service type'], 200);
        }
    }

    public function show($id)
    {
        $service_type = ServiceType::find($id);
        if ($service_type) {
            return response()->json([
                'status' => true,
                'message' => $service_type
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'No Emergency Service Type'
            ], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 403);
        };

        $serviceType = new ServiceType();
        $serviceType->name = $request->service_type;

        $save = $serviceType->save();
        if ($save) {
            return response()->json([
                'status' => true,
                'message' => 'success',
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'create service type unsuccess',
            ], 203);
        }
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ], 403);
        };

        $service_type = ServiceType::find($id);
        if ($service_type) {
            $service_type->name = $request->service_type ;

            $save = $service_type->save();
            if ($save) {
                return response()->json([
                    'status' => true,
                    'message' => $service_type,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'update unsuccess',
                ], 203);
            }
        } else{
            return response()->json([
                'status' => false,
                'message' => 'no service type is found',
            ], 200);
        }
    }

    public function destroy($id){
        $services = EmergencyService::where('service_type',$id)->get();
        if($services == null){
            $service_type = ServiceType::find($id);
            if($service_type){
                $service_type->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'service type delete successful',
                ],200);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'No service type',
                ],200);
            }
        }else{
            return response()->json([
                'status' => false,
                'message' => 'Service type is already used.',
            ],200);
        }
    }
}
