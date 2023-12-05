<?php

namespace App\Http\Controllers;

use App\Models\File;

class UserController extends Controller
{
    public function index()
    {
        $files = File::all();

        return view('user.index', ['files' => $files]);
    }

    public function show($name)
    {
        $file = File::where('name', $name)->firstOrFail();

        return view('user.show', compact('file'));
    }
}
