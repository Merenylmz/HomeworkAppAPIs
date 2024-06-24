<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Illuminate\Http\Request;

class HomeworkController extends Controller
{
    public function getHomeworkById(Request $req, $id){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if (!$infos["user"]) {return response()->json("Please give valid token");}
            $homework = Homework::find($id);

            return response()->json($homework);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addHomework(Request $req){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if (!$infos["user"]) {return response()->json("Please give valid token");}

            $newHomework = new Homework();
            $newHomework->title = $req->input("title");
            $newHomework->description = $req->input("description");
            $newHomework->subjectId = $req->input("subjectId");
            $newHomework->userId = $infos["userId"];
            if ($req->hasFile("fileUrl")) {
                $fileDetail = $req->file("fileUrl");
                $fileName = "file"."_".time()."_".$infos["userId"].$fileDetail->getClientOriginalName();
                $fileDetail->move(public_path("homeworks"), $fileName);
                $fileUrl = url("homeworks", $fileName);
                $newHomework->fileUrl = $fileUrl;
            }

            $newHomework->save();

            return response()->json(["status"=>"OK", "homework"=>$newHomework]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteHomework(Request $req, $id){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if (!$infos["user"]) {return response()->json("Please give valid token");}

            Homework::destroy($id);

            return response()->json(["status"=>"OK", "msg"=>"Homework Deleted"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editHomework(Request $req, $id){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if (!$infos["user"]) {return response()->json("Please give valid token");}

            $homework = Homework::find($id);
            $homework->title = $req->input("title");
            $homework->description = $req->input("description");
            if ($req->hasFile("fileUrl")) {
                $fileDetail = $req->file("fileUrl");
                $fileName = "file"."_".time()."_".$infos["userId"].$fileDetail->getClientOriginalName();
                $fileDetail->move(public_path("homeworks"), $fileName);
                $fileUrl = url("homeworks", $fileName);
                $homework->fileUrl = $fileUrl;
            }
            $homework->save();

            return response()->json(["status"=>"OK", "homework"=>$homework]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
