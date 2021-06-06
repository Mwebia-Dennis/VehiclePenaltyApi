<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\traits\Utils;

class SearchUserController extends Controller
{
    use Utils;


    public function index(Request $request) {

        $request->validate($rules = [
    
            'column' => 'required',
            'value' => 'required',

        ]);

        $user = new User();
        $columns = $this->getTableColumns($user->getTableName());

        if(in_array($request->column, $columns)) {

            $user = $user->where($request->column, 'LIKE', '%'.$request->value.'%');
            if(request()->has('sort_by')) {
                $user = $user->orderBy(request()->sort_by, 'DESC');
            }
            
            $perPage = (request()->has('per_page'))?request()->per_page:env('PER_PAGE');
            return response()->json($user->paginate($perPage));
    
        }
        return response()->json(["message", "could not find data"], 403);

    }
}
