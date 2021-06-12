<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Laravel\Passport\Client as OClient;

class AuthController extends Controller
{
    
    
    public function userDetails()
    {
        return response()->json(Auth::user(), 201);
    }


    public function login(Request $request) {

        $remember_me = (isset($request->remember_me))?$request->remember_me:false;
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember_me)) {
            
            return $this->getAccessAndRefreshTokens($request->email, $request->password);
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
        $profile_img = $this->genereteAvatar($user);
        $user->profile_img = $profile_img;

        $user->save();
        return response()->json(["message" => "successful sign up"], 201);
    }

    
    public function getAccessAndRefreshTokens(string $email, string $password) {
        
        // echo url('/');
        try {
            
            $oClient = DB::table('oauth_clients')->where('id', 2)->first();
            $http = new Client();


            // $response = $http->post('http://127.0.0.1:8001/oauth/token', [
            $response = $http->post(url('/oauth/token'), [
                'form_params' => [
                    'grant_type' => 'password',
                    'client_id' => $oClient->id,
                    'client_secret' => $oClient->secret,
                    'username' => $email,
                    'password' => $password,
                    'scope' => '*',
                ],
            ]);

            return json_decode((string) $response->getBody(), true);
        }catch(Exception $ex) {
            return response()->json(["error" => "Sorry an error occurred "+$ex->getMessage()], Response::HTTP_BAD_REQUEST);
        }
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
            return response()->json(["message" => "Email found"], 201);
        }
        return response()->json(["message" => "Could not find email"], 401);

    }

    public function updateProfile(Request $request) {
        $request->validate($rules = [

            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:50048',

        ]);

        
        $user = Auth::user();
        if($request->hasFile('profile_picture')) {
            
            $extension = $request->File('profile_picture')->getClientOriginalExtension();
            $imagePath = 'profile_picture'. $user->id . '.'.$extension;
            $image_url = $request->File('profile_picture')->storeAs('public/profile', $imagePath);
            $image_url= 'storage'. substr($image_url,strlen('public'));
            $user->profile_img = asset($image_url);
            if($user->isDirty()) {

                $user->save();

            }
        }
        return response()->json(["message" => "profile image updated successfully"], 201);
    }

    public function logout(Request $request) {
        
        $request->user()->token()->revoke();
        return response()->json(["message" => 'Successfully logged out'], 201);
    }

    public function genereteAvatar($user) {
        $profile_picture = (new Avatar)
            ->create(strtoupper($user->name))
            ->getImageObject()
            ->encode('png');

        
        Storage::disk('public')->put('profile' . $user->id . '/profile_picture.png',
            (string)$profile_picture);

        return Storage::url('profile'. $user->id . '/profile_picture.png');
    }
}
