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
    
            'value' => 'required',

        ]);

        $penalty = new Penalty();
        $columns = $this->getTableColumns($penalty->getTableName());

        $counter = 0;
        foreach($columns as $name) {
            if($counter == 0) {
                
                $penalty = $penalty->where($name, 'LIKE', '%'.$request->value.'%');
            }else {

                $penalty = $penalty->orWhere($name, 'LIKE', '%'.$request->value.'%');
            }
            $counter ++;
        }
        if(request()->has('sort_by')) {
            $penalty = $penalty->orderBy(request()->sort_by, 'DESC');
        }
        
        $perPage = (request()->has('per_page'))?request()->per_page:env('PER_PAGE');
        return response()->json($penalty->paginate($perPage), 201);

    }
}
