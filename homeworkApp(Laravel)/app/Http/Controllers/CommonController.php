<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jerry\JWT\JWT;

class CommonController extends Controller
{

    public static function verifyToken($token) {
        try {
            $infos = JWT::decode($token);

            $user = User::find($infos["userId"]);
            if(!$user){return response()->json(["status"=>"Is Not OK", "msg"=>"Users not Found"]);}
            if ($infos["expirationTime"] < Carbon::now()->timestamp) {return response()->json(["status"=>"Is Not OK", "msg"=>"Token expired or invalid"]);} 
            if($user->lastLoginToken != $token){return response()->json(["status"=>"Is Not OK", "msg"=>"Your token is invalid"]);}

            return ["user"=>$user, "status"=>"OK", "userId"=>$user->id];
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
