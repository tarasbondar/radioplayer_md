<?php

namespace App\Exports;

use App\Models\RadioStation;
use Maatwebsite\Excel\Concerns\FromCollection;

class RadioStationsExport implements FromCollection
{

    protected $name;
    protected $descr;

    public function __construct($name, $descr) {
        $this->name = $name;
        $this->descr = $descr;
    }

    public function collection() {
        $result = RadioStation::select('*');

        if (!empty($this->name)) {
            $result = $result->where('name', 'LIKE', "%{$this->name}%");
        }
        if (!empty($this->descr)) {
            $result = $result->where('description', 'LIKE', "%{$this->descr}%");
        }

        $result = $result->get();
        return $result;
    }

    /*public function headings(): array {
        return ["ID", "Name", "Email"];
    }*/
}
