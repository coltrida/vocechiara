<?php

namespace App\Imports;

use App\Models\Client;
use Maatwebsite\Excel\Concerns\ToModel;

class ClientsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Client([
            'cognome'     => $row[0],
            'nome'     => $row[1],
            'cod_fisc'    => $row[2],
            'indirizzo'    => $row[3],
            'cap'    => $row[4],
            'citta'    => $row[5],
            'provincia'    => $row[6],
            'telefono'    => $row[7],
            'tipo'    => $row[8],
            'user_id'    => $row[9],
            'fonte'    => $row[10],
            'created_at'    => $row[11],
            'updated_at'    => $row[12]
        ]);
    }
}
