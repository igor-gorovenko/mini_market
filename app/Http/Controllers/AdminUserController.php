<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function users()
    {
        $users = User::all();

        return view('admin.users.list', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(UserRequest $request)
    {
        $this->updateUser($request, null);

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

    public function update(UserRequest $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        $this->updateUser($request, $user);

        return redirect()->route('admin.users.list')->with('success', 'User updated');
    }

    public function destroy($slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();

        $user->delete();

        return redirect()->route('admin.users.list')->with('success', 'user deleted');
    }

    protected function updateUser($request, $user)
    {
        $data = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'is_admin' => (int)$request->input('is_admin'),
            'slug' => Str::slug($request->input('name'), '-'),
            'password' => Hash::make($request->input('password')),
        ];

        if ($user) {
            $user->update($data);
        } else {
            $user = User::create($data);
        }
    }
}
