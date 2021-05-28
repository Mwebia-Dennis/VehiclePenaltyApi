<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehiclePenaltyController extends Controller
{
    public function index(Vehicle $vehicle)
    {

        
        return response()->json($vehicle->penalty()->get(), 201);
        
    }
}
