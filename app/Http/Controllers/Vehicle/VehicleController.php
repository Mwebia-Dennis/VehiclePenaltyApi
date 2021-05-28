<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function index()
    {
        return response()->json(Vehicle::all(), 201);
    }
}
