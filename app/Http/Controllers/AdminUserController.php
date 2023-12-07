<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function users()
    {
        $users = User::all();

        return view('admin.users.list', compact('users'));
    }

    public function show($name)
    {
        $user = User::where('name', $name)->first();

        if (!$user) {
            abort(404);
        }

        return view('admin.users.show', compact('user'));
    }
}
