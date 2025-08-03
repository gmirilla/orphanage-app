<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Staff extends Model
{
    //
    protected $fillable = [
        'userid',
        'fname',
        'mname',
        'lname',
        'identificationtype',
        'identificationno',
        'profilephoto',
        'staffnotes',
        'active',
        'dob'
    ];

    public function getFullname()
    {
        $parts = array_filter([$this->fname, $this->mname, $this->lname]);
        return implode(' ', $parts);
    }

    public function getAge()
    {
        return Carbon::parse($this->dob)->age; // Returns an integer
    }
}
