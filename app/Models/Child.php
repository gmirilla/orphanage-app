<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Child extends Model
{
    //
    protected $fillable = ['fname','mname','lname','dateofbirth','gender','birthplace',
    'nationalityid', 'identificationtype', 'identificationno', 'admissiondate', 'profilephoto','note'
    ];

    public function getFullname()
    {
        $fullname= $this->fname. ' '. $this->mname. ' '.$this->lname;
        return $fullname;

    }

        public function getAge()
    {
        

        $diff = Carbon::now()->diffForHumans($this->dateofbirth, [
    'parts' => 3, // show up to 3 parts
    'short' => true, // e.g., "10mo 3d ago"
        ]);

        return $diff;


    }
}
