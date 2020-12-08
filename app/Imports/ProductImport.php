<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'matricola'     => $row[0],
            'id_listino'     => $row[1],
            'created_at'    => $row[2],
            'updated_at'    => $row[3]
        ]);
    }
}
