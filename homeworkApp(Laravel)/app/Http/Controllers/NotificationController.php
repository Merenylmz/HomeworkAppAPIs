<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function getNotifications(Request $req, $userid){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if(!$infos["user"]){return response()->json("Please give valid token");}

            $notifications = Notification::where(["userId"=>$userid, "isRead"=>false])->get();

            return response()->json($notifications);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addNotification(Request $req){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if(!$infos["user"]){return response()->json("Please give valid token");}

            $newNotification = new Notification();
            $newNotification->name = $req->input("name");
            $newNotification->from = $infos["userId"];
            $newNotification->toUser = $req->input("toUser");
            $newNotification->save();

            return response()->json(["status"=>"OK", "notification"=>$newNotification]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changeRead(Request $req, $notificationid){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if(!$infos["user"]){return response()->json("Please give valid token");}

            $notification = Notification::find($notificationid);
            $notification->isRead = true;
            $notification->save();

            return response()->json($notification);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
