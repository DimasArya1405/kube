<?php

namespace App\Exports;

use App\Models\KategoriKube;
use Maatwebsite\Excel\Concerns\FromCollection;

class KategoriExport implements FromCollection
{
    public function collection()
    {
        return KategoriKube::all();
    }
}