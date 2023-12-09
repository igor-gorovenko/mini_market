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

    public function show($slug)
    {
        $file = File::where('slug', $slug)->firstOrFail();

        return view('user.show', compact('file'));
    }
}
