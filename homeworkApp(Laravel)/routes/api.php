<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeworkController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::prefix("auth")->group(function(){
    Route::post("/register", [UserController::class, "register"]);
    Route::post("/login", [UserController::class, "login"]);
    Route::post("/forgotpassword", [UserController::class, "forgotPassword"]);
    Route::post("/newpassword", [UserController::class, "newPassword"]);
    Route::post("/edituser", [UserController::class, "editProfile"]);
    Route::get("/logout", [UserController::class, "logout"]);
});

Route::prefix("subjects")->group(function(){
    Route::get("/", [SubjectController::class, "getSubjects"]);
    Route::get("/homework", [SubjectController::class, "getSubjectWithHomework"]);
    Route::post("/", [SubjectController::class, "addSubject"]);
    Route::delete("/{id}", [SubjectController::class, "deleteSubject"]);
    Route::put("/edit/{id}", [SubjectController::class, "editSubject"]);
    Route::post("/addhomework", [SubjectController::class, "addHomeworkInSubject"]);
});

Route::prefix("homeworks")->group(function(){
    Route::get("/{id}", [HomeworkController::class, "getHomeworkById"]);
    Route::post("/", [HomeworkController::class, "addHomework"]);
    Route::delete("/{id}", [HomeworkController::class, "deleteHomework"]);
    Route::post("/edit/{id}", [HomeworkController::class, "editHomework"]);
    //put ile yapıldığında bilgiler ulaşmıyor
});

Route::post("/addcomment/{homeworkid}", [CommentController::class, "addComment"]);

Route::prefix("/notifications")->group(function(){
    Route::get("/{userid}", [NotificationController::class, "getNotifications"]);
    Route::post("/add", [NotificationController::class, "addNotification"]);
    Route::get("/changeread/{notificationid}", [NotificationController::class, "changeRead"]);
});