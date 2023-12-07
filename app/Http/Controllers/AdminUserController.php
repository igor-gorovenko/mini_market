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

    public function edit($name)
    {
        $user = User::where('name', $name)->first();

        if (!$user) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $name)
    {

        $user = User::where('name', $name)->firstOrFail();

        // Валидация
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
        ]);

        // Обновление данных в базе
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        return redirect()->route('admin.users.show', ['name' => $user->name])->with('success', 'User updated');
    }
}
