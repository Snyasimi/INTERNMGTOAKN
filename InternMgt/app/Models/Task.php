<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
     public function uniqueIds(){
        return ['AssignedBy','AssignedTo'];
    }


    protected $hidden = ['AssignedBy','TaskDescription','created_at','updated_at'];

    public function comments(){
        return $this->hasMany(CommentAndRemark::class,"task_id");
    }

    public  function Assignedby(){
        return $this->belongsTo(User::class,'AssignedBy');
    }
    public function Assignedto()
    {
        return $this->belongsTo(User::class,'AssignedTo');
    }
}
