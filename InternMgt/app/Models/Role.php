<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'Role'
    ];

    public $timestamps =false;
    
    public function role(){
        return $this->hasMany(User::class,'Role');
    }
}
