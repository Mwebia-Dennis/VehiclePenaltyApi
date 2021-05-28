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

            return response()->json($user->where($request->column, 'LIKE', '%'.$request->value.'%')->get(), 201);
    
        }
        return response()->json(["message", "could not find data"], 403);

    }
}
