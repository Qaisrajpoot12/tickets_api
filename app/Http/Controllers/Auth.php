<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\users;
use App\Models\accessTokens;

class Auth extends Controller
{
    public function register(Request $req){
        // $user = $req->validate([
        //     "username"         => "required|alpha",
        //     "firstname"        => "required|alpha",
        //     "lastname"         => "required|alpha",
        //     "password"         => "required",
        //     "confirm_password" => "required|same:password",
        //     "salt"             => "required"
        // ]);
        $array = [
            "username"           => $req->username,
            "username_canonical" => $req->username,
            "email"              => $req->email,
            "email_canonical"    => $req->email,
            "enabled"            => 0,
            "salt"               => $req->salt,
            "password"           => hash::make($req->password),
            "roles"              => "misc",
            "firstname"          => $req->firstname,
            "lastname"           => $req->lastname,
            "slug"               => $req->username,
            "created_at"         => date('y-m-d H:i:s'),
        ];
        $user = users::create($array);
        return $user ? response(["msg"=>"We've sent you and Confirmation Email on $req->email",'status'=>200],200) : response(["msg"=>"Something went wrong! Please Try Again later",'status'=>201],200);
    }

    public function login(Request $req){ 
        $user = users::where(['email'=>$req->email])->get()->first();
        if($user){
            if(hash::check($req->password,$user->password)){
                if(accessTokens::where(['tokenable_id'=>$user->id])->count() == 0){
                    return response([
                        'user_details' => $user,
                        'access_token' => $user->createToken($user->username)->plainTextToken,
                        'msg'          => 'Logged In Successfully'
                    ],200);
                }else{
                    return response(['msg'=>'You have Already Logged In'],200);
                }
            }else{
                return response(['msg'=>'The Password You entered is Wrong!!!'],200);
            }
        }else{
            return response(['msg'=>'No User Found Associated with this Email'],200);
        }
    }

    public function logout(Request $req){
        auth('sanctum')->user()->tokens()->delete();
        return response(['msg'=>'You have Logged Out Successfully'],200);
    }

    public function loginCheck(Request $req){
        return users::check($req->id);   
    }



}
