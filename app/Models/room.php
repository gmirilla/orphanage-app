<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class room extends Model
{
    //
    protected $fillable = [
        'roomnumber',
        'roomtype',
        'roomclasssification',
        'capacity',
        'status',
        'roomnotes'
    ];

    public function getoccupancyBadge()
    {
         switch (true) {
                            case (count($this->getOccupant())<$this->capacity) :
                                # code...
                                $badgeClassRoom='success';
                                break;
                            case (count($this->getOccupant())==$this->capacity):
                                # code...
                                $badgeClassRoom='warning';
                                break;
                            case (count($this->getOccupant())>$this->capacity):
                                # code...
                                $badgeClassRoom='danger';
                                break;                            
                            default:
                                # code...
                                break;
                            }
        return $badgeClassRoom;
    }

        public function getOccupants()
    {
        $occupants = accomodationrecord::where('dormroom', $this->id)->get();
        return $occupants;
    }
}
