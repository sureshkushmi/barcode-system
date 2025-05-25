<?php

namespace App\Exports;
use App\Http\Controllers\UserController;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function view(): View
    {
        return view('exports.users', [
            'users' => $this->users
        ]);
    }
}
