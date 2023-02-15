<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentAndRemark extends Model
{
    use HasFactory;
    public function uniqueIds()
    {
        return ['user_id'];
    }
    protected $guarded = [];

    public function Task(){

        return $this->belongsTo(Task::class);
    }
    public function MadeBy(){
        return $this->belongsTo(User::class,'user_id');
    }
    
}

