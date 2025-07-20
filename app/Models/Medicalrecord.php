<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicalrecord extends Model
{
    //
    protected $fillable=['child_id','allergy','medication','doctorname', 'doctorcontact', 'medicalnote']; 

}
