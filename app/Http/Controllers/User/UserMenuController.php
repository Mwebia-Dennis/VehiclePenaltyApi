<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class UserMenuController extends Controller
{
    
    public function store(Request $request, $user_id)
    {
        
        $request->validate($rules = [
    
            'name' => 'required|unique:menu',

        ]);

        $menu = new Menu();
        $user = Auth::user();
        $menu->name = $request->name;
        $menu->added_by = $user->id;
        $menu->save();
        return response()->json(["menu added successfully"], 201);


    }
}
