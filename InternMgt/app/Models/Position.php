<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;
    protected $fillable = [
        'Position'
    ];
    public $timestamps =false;

    public function Position(){

        return $this->hasMany(User::class,'Position');
    }
}
