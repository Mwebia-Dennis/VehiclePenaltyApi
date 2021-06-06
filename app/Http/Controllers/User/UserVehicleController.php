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
            'owner_name' => 'required|max:150',
            'owner_surname' => 'required|max:150',
            'duty_location' => 'required|max:150',
            'unit' => 'required|max:150',

        ]);
        $vehicle = new Vehicle();
        $vehicle->plate_number = $request->plate_number;
        $vehicle->owner_name = $request->owner_name;
        $vehicle->owner_surname = $request->owner_surname;
        $vehicle->duty_location = $request->duty_location;
        $vehicle->unit = $request->unit;

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
            'owner_name' => 'required',
            'owner_surname' => 'required',
            'duty_location' => 'required',
            'unit' => 'required',

        ]);
        $vehicle->plate_number = $request->plate_number;
        $vehicle->owner_name = $request->owner_name;
        $vehicle->owner_surname = $request->owner_surname;
        $vehicle->duty_location = $request->duty_location;
        $vehicle->unit = $request->unit;
        if($vehicle->isDirty()) {
            
            $vehicle->save();

        }

        return response()->json(["message" => " Vehicle added successfully"], 201);
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
