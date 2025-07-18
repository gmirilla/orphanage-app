<?php

namespace App\Imports;

use App\Models\country;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CountryImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new country([
            //
            'name'     => $row['name'],
            'abbrevation'=> $row['abbrevation'],
            'region'        => $row['region'],
        ]);
    }
}
