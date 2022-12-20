<?php

namespace App\Http\Controllers\Dashboard\Organiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\support\facades\DB;

class OrganiserUser extends Controller
{
    public function getOrganizerDetails(Request $req)
    {
        $user = DB::table('eventic_organizer')
                    ->where([
                        'id' => $req->id
                    ])
                    ->get();
        return response(['count'=>count($user),'status'=>200,'user'=>$user],200);
    }
}
