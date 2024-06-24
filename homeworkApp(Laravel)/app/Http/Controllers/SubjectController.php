<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function getSubjects(Request $request){
        try {
            $subjects = Subject::all();

            return response()->json($subjects);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSubjectWithHomework(Request $request, $id){
        try {
            $subject = Subject::find($id);

            return response()->json($subject->homeworks);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addSubject(Request $req){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            $user = User::find($infos["userId"]);
            if(!$infos["userId"] && !$user && !$user->isAdmin){return response()->json("Please give valid or not expired token");}

            $newSubject = new Subject([
                "title"=>$req->input("title")
            ]);
            $newSubject->save();

            return response()->json($newSubject);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteSubject(Request $req, $id){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            $user = User::find($infos["userId"]);
            if(!$infos["userId"] && !$user && !$user->isAdmin){return response()->json("Please give valid or not expired token");}

            Subject::destroy($id);

            return response()->json(["status"=>"OK", "msg"=>"Deleted"]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function editSubject(Request $req, $id){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            $user = User::find($infos["userId"]);
            if(!$infos["userId"] && !$user && !$user->isAdmin){return response()->json("Please give valid or not expired token");}

            $subject = Subject::find($id);
            $subject->title = $req->input("title");
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addHomeworkInSubject(Request $req, $id){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            $user = User::find($infos["userId"]);
            if(!$infos["userId"] && !$user && !$user->isAdmin){return response()->json("Please give valid or not expired token");}

            $subject = Subject::find($id);
            
            $homeworksArray = json_decode($subject->homeworks, true);
            array_push($homeworksArray, $req->input("homeworkId"));
            $subject->homeworks = json_encode($homeworksArray);
            $subject->save();

            return response()->json($subject->homeworks);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
