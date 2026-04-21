<?php

namespace App\Exports;

use App\Models\Pendamping;
use Maatwebsite\Excel\Concerns\FromCollection;

class PendampingExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Pendamping::all();
    }
}
