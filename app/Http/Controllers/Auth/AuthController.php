<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    

    public function login(Request $request) {

        $remember_me = (isset($request->remember_me))?$request->remember_me:false;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
            
            return response()->json(["message" => "Successful login"], 201);
        }
        
        //invalid credentials

        return response()->json(["error" => "Unauthorised access, check your credentials or sign up today"], 401);
    }

    
    public function signUp(Request $request) {
        $request->validate($rules = [
    
            'name' => 'required|max:150',
            'surname' => 'required|max:150',
            'email' => 'required|unique:users|max:150',
            'password' => 'required|min:6',

        ]);

        $user = new User();
        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();
        return response()->json(["message" => "successful sign up"], 201);
    }
    
    public function forgotPassword(Request $request) {
        
        $request->validate($rules = [

            'email' => 'required',
            'new_password' => 'required|min:6',

        ]);

        $user = new User();
        $user = $user->where('email', $request->email)->first();
        if($user) {

            $user->password = bcrypt($request->new_password);
        
            if($user->isDirty()) {
                $user->save();
            }
            return response()->json(["message" => "password changed successfully"], 201);
        }
        
        return response()->json(["message" => "unauthorised"], 401);

    }

    public function checkEmail(Request $request) {
        
        $request->validate($rules = [

            'email' => 'required',

        ]);

        $user = User::where('email', '=', $request->email)->get();
        
        if(sizeof($user) > 0) {
            // return response()->json(["message" => "Email found"], 201);
            return response()->json($user, 201);
        }
        return response()->json(["message" => "Could not find email"], 401);

    }
}
