<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;

class AdminUserController extends Controller
{
    public function users()
    {
        $users = User::all();

        return view('admin.users.list', compact('users'));
    }

    public function show($slug)
    {
        $user = User::where('slug', $slug)->first();

        if (!$user) {
            abort(404);
        }

        return view('admin.users.show', compact('user'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'is_admin' => 'required|boolean',
        ]);

        // Создание нового пользователя
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'is_admin' => (int)$request->input('is_admin'),
            'slug' => Str::slug($request->input('name')),
        ]);

        return redirect()->route('admin.users.list')->with('success', 'User created');
    }

    public function edit($slug)
    {
        $user = User::where('slug', $slug)->first();

        if (!$user) {
            abort(404);
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $slug)
    {

        $user = User::where('slug', $slug)->firstOrFail();

        // Валидация
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'is_admin' => 'required|boolean',
        ]);

        // Обновление данных в базе
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_admin' => (int)$request->input('is_admin'),
            'slug' => Str::slug($request->input('name'), '-'),
        ]);

        return redirect()->route('admin.users.show', ['slug' => $user->slug])->with('success', 'User updated');
    }

    public function destroy($name)
    {
        $user = User::where('name', $name)->firstOrFail();

        $user->delete();

        return redirect()->route('admin.users.list')->with('success', 'user deleted');
    }
}
