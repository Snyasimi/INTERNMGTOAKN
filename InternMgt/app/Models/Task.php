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


    protected $guarded = [];

    public function comments(){
        return $this->hasMany(CommentAndRemark::class,"task_id");
    }

    public  function AssignedBy(){
        return $this->belongsTo(User::class);
    }
    public function AssignedTo()
    {
        return $this->belongsTo(User::class);
    }
}
