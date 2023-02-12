<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    use HasUlids;
    public $incrementing = false;
    protected $primaryKey = "user_id";
    protected $KeyType = "string";
    public function uniqueIds(){
        return ['user_id'];
    }
    protected $fillable = [
        'Name',
        'Email',
        'Phone',
        'department_id',
        'password',
        'Supervisor',
        'Role',
        'Status'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id'
    ];

    public function Attachee(){

        return $this->hasMany(User::class);
    }

    public function Supervisor(){

        return $this->belongsTo(User::class,'Supervisor');
    }

    public function Department(){

        return $this->belongsTo(Department::class);
    }
    public function Assign()
    {
        return $this->hasMany(Task::class, 'AssignedBy');
    }
    public function AssignTo()
    {
        return $this->hasMany(Task::class, 'AssignedTo');
    }

    public function Comments(){
        return $this->hasMany(CommentAndRemark::class,);
    }
    

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
