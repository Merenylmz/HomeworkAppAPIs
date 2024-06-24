<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPassMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Jerry\JWT\JWT;
use Illuminate\Support\Str;



class UserController extends Controller
{


    public function register(Request $request){
        $user = User::where("email", $request->input("email"))->first();
        if ($user) {
            return response()->json("This email already exists");
        }

        $user = new User();
        $user->name= $request->input("name");
        $user->email = $request->input("email");
        $user->password= Hash::make($request->input("password"));
        $user->save();

        return response()->json(["status"=>"OK", "msg"=>"User Saved"]);
    }


    public function login(Request $request){
        try {
            $user = User::where("email", $request->input("email"))->first();
            if(!$user){
                return response()->json("Please valid email");
            }

            $status = Hash::check($request->input("password"), $user->password);
            if (!$status) {
                return response()->json("Please check your password its wrong");
            }

            $authToken = JWT::encode([
                "userId"=> $user->id,
                "expirationTime"=>Carbon::now()->addHours(3)->timestamp
            ]);

            $user->lastLoginToken = $authToken;

            $user->save();

            return response()->json([
                "status"=>"OK",
                "token"=>$authToken
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function forgotPassword(Request $request){
        try {
            $infos = CommonController::verifyToken($request->query("token"));
            if(!$infos["userId"]){return response()->json("Please enter valid token");}
            $user = User::find($infos["userId"]);
            
            $token = Str::random(32);
            $user->remember_token = $token;
            $user->rememberTokenExpiration = Carbon::now();
            $user->save();

            Mail::to($user->email)->send(new ForgotPassMail($token));

            return response()->json(["status"=>"OK", "msg"=>"Please Check your mailbox"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function newPassword(Request $req){
        try {
            $user = User::where("remember_token", $req->query("token"))->get()[0];
            if(!$user){return response()->json("Please give valid token");}

            $user->password = Hash::make($req->input("password"));
            $user->remember_token = null;
            $user->rememberTokenExpiration = null;
            $user->save();

            return response()->json(["status"=>"OK", "msg"=>"Password Changed"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editProfile(Request $req){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            $user = User::find($infos["userId"]);
            if(!$user && !$infos["userId"]){return response()->json("Please give valid or not expired token");}

            $user->bioTxt = $req->input("biotxt");
            if ($req->hasFile("profilePhotoUrl")) {
                $photoFile = $req->file("profilePhotoUrl");
                $photoFileName = "photo_".time()."_".$user->id.$photoFile->getClientOriginalName();
                $photoFile->move(public_path("profilePhotos"), $photoFileName);
                $photoUrl = url("profilePhotos", $photoFileName);
                $user->profilePhoto = $photoUrl;
            }

            $user->save();

            return response()->json(["status"=>"OK", "profilePhoto"=>$user->profilePhoto, "bioTxt"=>$user->bioTxt]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function logout(Request $req){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            $user = User::find($infos["userId"]);
            if(!$user && !$infos["userId"]){return response()->json("Please give valid or not expired token");}

            $user->lastLoginToken = null;
            $user->save();


            return response()->json(["status"=>"OK", "msg"=>"Exited"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

}