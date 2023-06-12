<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{

    protected $username;
    protected $email;
    protected $registered_from;
    protected $registered_to;
    protected $logged_from;
    protected $logged_to;

    public function __construct($args) {
        $this->username = $args['username'];
        $this->email = $args['email'];
        $this->registered_from = $args['registered_from'];
        $this->registered_to = $args['registered_to'];
        $this->logged_from = $args['logged_from'];
        $this->logged_to = $args['logged_to'];
    }

    public function collection() {
        $result = User::select('*');

        if (!empty($this->username)) {
            $result = $result->where('name', 'LIKE', "%{$this->username}%");
        }
        if (!empty($this->email)) {
            $result = $result->where('email', 'LIKE', "%{$this->email}%");
        }
        if (!empty($this->registered_from)) {
            $result = $result->where('created_at', '>=', $this->registered_from . ' 00:00:00');
        }
        if (!empty($this->registered_to)) {
            $result = $result->where('created_at', '<=', $this->registered_to . ' 23:59:59');
        }
        if (!empty($this->logged_from)) {
            $result = $result->where('last_login_at', '>=', $this->logged_from . ' 00:00:00');
        }
        if (!empty($this->logged_to)) {
            $result = $result->where('last_login_at', '<=', $this->logged_to . ' 23:59:59');
        }

        $result = $result->get();
        return $result;
    }
}
