<?php

namespace App\Http\Controllers;

use App\Models\eventic_organizer;
use App\Models\eventic_user;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    
    public function SignupAsAttende(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'username' => 'required',
            // 'slug' => 'required',
            // "username_canonical" => 'required',
            // "email_canonical"    => 'required|email',
            "roles"              => "required",
            "firstname"          => 'required',
            "lastname"           => 'required',
            // "slug"               => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            // $api_token = hash('sha256', time());
            $api_token =  base64_encode('api_key_secret_for_app');


            $user = eventic_user::create([
                // 'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'api_token' => $api_token,
                'username' => $request->username,
                "username_canonical" => $request->username,
                "email_canonical"    => $request->email,
                "enabled"            => 0,
                "roles"              => $request->roles,
                "firstname"          => $request->firstname,
                "lastname"           => $request->lastname,
                "slug"               => $request->username,
                "created_at"         => date('y-m-d H:i:s'),
            ]);

            return $user;
        }
    }
    public function SignupAsOrgnizer(Request $request)
    {



        $validator = Validator::make($request->all(), [
            // 'organization' => 'required|email',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'username' => 'required',
            "roles"              => "required",
            // "firstname"          => 'required',
            // "lastname"           => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            // $api_token = hash('sha256', time());
            $api_token =  base64_encode('api_key_secret_for_app');


            $role  = eventic_organizer::create([
                'name' => $request->name,
                'slug' => $request->name,
                'email' => $request->email,
                'phone ' => $request->phone,
                'views' => $request->views,
                'showvenuesmap'           => $request->showvenuesmap,
                'showvenuesmap'           => $request->showvenuesmap,
                'showreviews'           => $request->showreviews,
                'allow_tap_to_check_in_on_scanner_app'           => $request->allow_tap_to_check_in_on_scanner_app,
                'show_event_date_stats_on_scanner_app'           => $request->allow_tap_to_check_in_on_scanner_app,
                'created_at'         => date('y-m-d H:i:s'),
            ]);
            if (isset($role)) {
                $user = eventic_user::create([
                    'organizer_id' => $role->id,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    // 'api_token' => $api_token,
                    'username' => $request->username,
                    "username_canonical" => $request->username,
                    "email_canonical"    => $request->email,
                    "enabled"            => 0,
                    "roles"              => $request->roles,
                    "firstname"          => $request->firstname,
                    "lastname"           => $request->lastname,
                    "slug"               => $request->username,
                    "created_at"         => date('y-m-d H:i:s'),
                ]);
            }
            $check_it = "";
            if (isset($user)) {
                $role->user_id = $user->id;
                $role->update();
                $check_it = "yes";
            }
            if ($check_it == "yes") {
                $user->organizer_id = $role->id;
                $user->update();
            }

            // return $role;
            return $role;
        }
    }
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'api_token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {


            $login_token = Hash::make($request->password);
            $checkUser = eventic_user::where('email', $request->email)->first();
            // $checkUser = eventic_organizer::where('email', $request->email)->first();
            if ($checkUser && $request->email == $checkUser->email) {
                if (Hash::check($request->password, $checkUser->password)) {
                    $checkUser->login_token = $login_token;
                    $checkUser->update();
                    return $checkUser;
                } else {
                    return  response()->json(['error' => 'check your password'], 422);
                }
            } else {
                return response()->json(['error' => 'email not found'], 422);
            }
        }
    }





    // ......................... update user profile ............................


    public function update_profile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'name' => 'required',
            // 'email' => 'required|email',
            // 'password' => 'required',
            'api_token' => 'required',
            'login_token' => 'required'
        ]);



        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {
            $user = eventic_user::where('id', $id)->first();
            if ( $user->login_token == $request->login_token) {



                $organizer = eventic_organizer::where('user_id', $id)->first();

                $organizer->name = $request->name ? $request->name : $organizer->name;
                $organizer->email = $request->email ? $request->email : $organizer->email;
                $organizer->logo_name = $request->logo_name ? $request->logo_name : $organizer->logo_name;
                $organizer->slug  = $request->slug  ? $request->slug  : $organizer->slug;

                // unlink(public_path('uploads/' . $obj->photo));

                if ($request->hasFile('logo_name')) {
                    $ext = $request->file('logo_name')->extension();
                    $final_name =  time() . '.' . $ext;

                    $request->file('logo_name')->move(public_path('uploads/'), $final_name);
                    $organizer->logo_name = $final_name;
                }


                // ...................... user ..............................
                $user->password = $request->password ? Hash::make($request->password) : $user->password;
                $user->username = $request->username ? $request->username : $user->username;
                $user->username_canonical  = $request->username_canonical  ? $request->username_canonical  : $user->username_canonical;
                $user->slug  = $request->slug  ? $request->slug  : $user->slug;
                $user->email_canonical   = $request->email_canonical   ? $request->email_canonical   : $user->email_canonical;
                $user->email   = $request->email   ? $request->email   : $user->email;
                $user->gender   = $request->gender   ? $request->gender   : $user->gender;
                $user->firstname   = $request->firstname   ? $request->firstname   : $user->firstname;
                $user->lastname   = $request->lastname   ? $request->firstname   : $user->firstname;

                $organizer->update();
                $user->update();


                return $organizer;
            }else{
                return  response()->json(['error' => 'invalid login token'], 401);
            }
        }
    }
}
