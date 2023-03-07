<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applicants extends Model
{
    use HasFactory;
    protected $fillable = [
        'Name',
        'Email',
        'PhoneNumber',
        'Position',
        'url_to_cv_file',
        'url_to_attachment_letter',
        'ApplicationStatus'

    ];

    protected $hidden = [
        //'url_to_cv_file',
        //"url_to_attachment_letter"
    ];


    //protected $hidden = ['Email','PhoneNumber'];
    protected $table="applicants";
}
