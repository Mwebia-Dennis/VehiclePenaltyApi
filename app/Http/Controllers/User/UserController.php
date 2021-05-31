<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    

    public function index() {
        return response()->json(User::all());
    }
    public function show(User $user)
    {
        return response()->json($user, 201);
    }
    public function update(Request $request, User $user)
    {

        $request->validate($rules = [
    
            'name' => 'required|max:150',
            'surname' => 'required|max:150',

        ]);

        $user->name = $request->name;
        $user->surname = $request->surname;

        if($user->isDirty()) {
            $user->save();
        }
        return response()->json(["message" => "Profile Details updated successfully"], 201);
    }
}
