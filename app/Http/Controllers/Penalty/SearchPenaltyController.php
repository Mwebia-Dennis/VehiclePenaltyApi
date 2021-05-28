<?php

namespace App\Http\Controllers\Penalty;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penalty;
use App\traits\Utils;

class SearchPenaltyController extends Controller
{
    use Utils;


    public function index(Request $request) {

        $request->validate($rules = [
    
            'column' => 'required',
            'value' => 'required',

        ]);

        $penalty = new Penalty();
        $columns = $this->getTableColumns($penalty->getTableName());

        if(in_array($request->column, $columns)) {

            return response()->json($penalty->where($request->column, 'LIKE', '%'.$request->value.'%')->get(), 201);
    
        }
        return response()->json(["message", "could not find data"], 403);

    }
}
