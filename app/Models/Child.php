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
    'syntax' => Carbon::DIFF_ABSOLUTE, // removes "ago",
        ]);

        return $diff;
    }
    public function getHobbiesstring()
    {
    
    $hobbies = Development::where('child_id', $this->id)->pluck('hobbies');

    $filtered = $hobbies->filter(function ($hobby) {
        return !empty($hobby);
    });

    return $filtered->isNotEmpty() ? $filtered->implode(', ') : 'No hobbies on file';

    }
    
    public function getspecialneedsstring()
    {
    
    $specialneeds = Development::where('child_id', $this->id)->pluck('specialneeds');
        $filtered = $specialneeds->filter(function ($hobby) {
        return !empty($specialneeds);
    });

    return $filtered->isNotEmpty() ? $filtered->implode(', ') : 'No Special Needs on file';
    }

        public function getAllergystring()
    {
    
    $specialneeds = Development::where('child_id', $this->id)->pluck('specialneeds');
        $filtered = $specialneeds->filter(function ($hobby) {
        return !empty($specialneeds);
    });

    return $filtered->isNotEmpty() ? $filtered->implode(', ') : 'No Allergy on file';
    }

    public function getEducation()
    {
    
    $education = Education::where('child_id', $this->id)->orderBy('academicyear','desc')->get();

    return $education;
    }

        public function getMedical()
    {   
    $medical = Medicalrecord::where('child_id', $this->id)->orderBy('created_at','desc')->get();

    return $medical;
    }

    public function getBackground()
    {   
    $background = backgroundrecord::where('child_id', $this->id)->orderBy('created_at','desc')->get();

    return $background;
    }

    public function getAccomodation()
    {   
    $accomodation = accomodationrecord::where('child_id', $this->id)->orderBy('created_at','desc')->get();

    return $accomodation;
    }


    
}

