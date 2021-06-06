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

            $penalty = $penalty->where($request->column, 'LIKE', '%'.$request->value.'%');
            if(request()->has('sort_by')) {
                $penalty = $penalty->orderBy(request()->sort_by, 'DESC');
            }
            
            $perPage = (request()->has('per_page'))?request()->per_page:env('PER_PAGE');
            return response()->json($penalty->paginate($perPage));
    
        }
        return response()->json(["message", "could not find data"], 403);

    }
}
