<?php

namespace App\Exports;

use App\Models\RadioStation;
use Maatwebsite\Excel\Concerns\FromCollection;

class RadioStationsExport implements FromCollection
{
    public function collection()
    {
        return RadioStation::all();
    }
}
