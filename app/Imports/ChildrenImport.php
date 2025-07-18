<?php

namespace App\Imports;

use App\Models\Child;
use Maatwebsite\Excel\Concerns\ToModel;

class ChildrenImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Child([
            //
        ]);
    }
}
