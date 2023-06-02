<?php

namespace App\Exports;

use App\Models\Podcast;
use Maatwebsite\Excel\Concerns\FromCollection;

class PodcastsExport implements FromCollection
{

    protected $name;
    protected $descr;

    public function __construct($name, $descr) {
        $this->name = $name;
        $this->descr = $descr;
    }

    public function collection() {
        $result = Podcast::select('*');

        if (!empty($this->name)) {
            $result = $result->where('name', 'LIKE', "%{$this->name}%");
        }
        if (!empty($this->descr)) {
            $result = $result->where('description', 'LIKE', "%{$this->descr}%");
        }

        $result = $result->get();
        return $result;
    }
}
