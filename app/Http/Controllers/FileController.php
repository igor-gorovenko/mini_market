<?php

namespace App\Http\Controllers;

use App\Models\File;

class FileController extends Controller
{
    public function index()
    {
        $files = File::all();

        return view('user.index', ['files' => $files]);
    }

    public function show($id)
    {
        $file = File::findOrFail($id);

        return view('user.show', compact('file'));
    }
}
