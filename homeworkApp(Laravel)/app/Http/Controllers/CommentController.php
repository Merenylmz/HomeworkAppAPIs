<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Homework;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(Request $req, $homeworkid){
        try {
            $infos = CommonController::verifyToken($req->query("token"));
            if(!$infos["user"]){return response()->json("Please give valid token");}

            $newComment = new Comment();
            $newComment->name = $req->input("name");
            $newComment->description = $req->input("description");
            $newComment->userId = $infos["userId"];
            $newComment->homeworkId = $homeworkid;
            $newComment->save();

            $homework = Homework::find($homeworkid);
            $homeworkCommentArray = json_decode($homework->comments);
            array_push($homeworkCommentArray, $newComment->id);
            $homework->comments = json_encode($homeworkCommentArray);

            $homework->save();

            return response()->json(["status"=>"OK", "comment"=>$newComment->description]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
