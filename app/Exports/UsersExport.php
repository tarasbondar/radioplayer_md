<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{

    protected $username;
    protected $email;

    public function __construct($username, $email) {
        $this->username = $username;
        $this->email = $email;
    }

    public function collection() {
        $result = User::select('*');

        if (!empty($this->username)) {
            $result = $result->where('name', 'LIKE', "%{$this->username}%");
        }
        if (!empty($this->email)) {
            $result = $result->where('email', 'LIKE', "%{$this->email}%");
        }

        $result = $result->get();
        return $result;
    }
}
