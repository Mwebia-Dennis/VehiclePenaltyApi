<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\traits\Utils;

class SearchVehicleController extends Controller
{
    use Utils;


    public function index(Request $request) {

        $request->validate($rules = [
    
            'column' => 'required',
            'value' => 'required',

        ]);

        $vehicle = new Vehicle();
        $columns = $this->getTableColumns($vehicle->getTableName());

        if(in_array($request->column, $columns)) {

            return response()->json($vehicle->where($request->column, 'LIKE', '%'.$request->value.'%')->get(), 201);
    
        }
        return response()->json(["message", "could not find data"], 403);

    }
}
