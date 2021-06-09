<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;


class UserVehicleController extends Controller
{
    public function index($id)
    {
        
        $user = Auth::user();

        $user = $user->myVehicle();
        if(request()->has('sort_by')) {
            $user = $user->orderBy(request()->sort_by, 'DESC');
        }
        
        $perPage = (request()->has('per_page'))?request()->per_page:env('PER_PAGE');
        return response()->json($user->paginate($perPage));
    }
    public function store(Request $request)
    {
        
        $request->validate($rules = [
    
            'plate_number' => 'required|unique:vehicles|max:250',               
            'vehicle_group' => 'required|max:150',
            'brand_model' => 'required|max:150',
            'chassis_number' => 'required|max:150',
            'motor_number' => 'required|max:150',
            'model_year'=> 'required|max:150',
            'color' => 'required|max:150',
            'file_number' => 'required|max:150',
            'tag' => 'required|max:150',
            'reception_type' => 'required|max:150',
            'delivery_date' => 'required|max:150',
            'asset_number' => 'required|max:150',

        ]);
        $vehicle = new Vehicle();
        $vehicle->plate_number = $request->plate_number;
        $vehicle->vehicle_group = $request->vehicle_group;
        $vehicle->brand_model = $request->brand_model;
        $vehicle->chassis_number = $request->chassis_number;
        $vehicle->motor_number = $request->motor_number;
        $vehicle->model_year = $request->model_year;
        $vehicle->color = $request->color;
        $vehicle->file_number = $request->file_number;
        $vehicle->tag = $request->tag;
        $vehicle->reception_type = $request->reception_type;
        $vehicle->delivery_date = $request->delivery_date;
        $vehicle->asset_number = $request->asset_number;

        $user = Auth::user();
        $vehicle->added_by = $user->id;
        $vehicle->addedBy()->associate($vehicle->added_by);
        $vehicle->save();

        return response()->json(["message" => " Vehicle added successfully"], 201);
    }
    public function update(Request $request, $user_id, Vehicle $vehicle)
    {

        $request->validate($rules = [
    
            'plate_number' => 'required:unique:vehicles',               
            'vehicle_group' => 'required|max:150',
            'brand_model' => 'required|max:150',
            'chassis_number' => 'required|max:150',
            'motor_number' => 'required|max:150',
            'model_year'=> 'required|max:150',
            'color' => 'required|max:150',
            'file_number' => 'required|max:150',
            'tag' => 'required|max:150',
            'reception_type' => 'required|max:150',
            'delivery_date' => 'required|max:150',
            'asset_number' => 'required|max:150',

        ]);
        $vehicle->plate_number = $request->plate_number;
        $vehicle->vehicle_group = $request->vehicle_group;
        $vehicle->brand_model = $request->brand_model;
        $vehicle->chassis_number = $request->chassis_number;
        $vehicle->motor_number = $request->motor_number;
        $vehicle->model_year = $request->model_year;
        $vehicle->color = $request->color;
        $vehicle->file_number = $request->file_number;
        $vehicle->tag = $request->tag;
        $vehicle->reception_type = $request->reception_type;
        $vehicle->delivery_date = $request->delivery_date;
        $vehicle->asset_number = $request->asset_number;

        if($vehicle->isDirty()) {
            
            $vehicle->save();

        }

        return response()->json(["message" => " Vehicle updated successfully"], 201);
    }
    public function destroy($user_id, Vehicle $vehicle)
    {
        $user = Auth::user();
        if($user->id == $vehicle->added_by){
            $vehicle->delete();
            return response()->json(["message" => " Vehicle deleted successfully"], 201);
        }else{
            return response()->json(["message" => " You cannot delete this vehicle"], 403);
        }

    }
}
