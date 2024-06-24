<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = ["name", "description", "userId", "homeworkId"];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function homework(){
        return $this->belongsTo(Homework::class);
    }
}
