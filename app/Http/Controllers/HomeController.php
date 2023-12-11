<?php

namespace App\Http\Controllers;

use App\Models\File;

class HomeController extends Controller
{
    public function index()
    {
        $files = File::all();

        return view('site.index', ['files' => $files]);
    }

    public function show($slug)
    {
        $file = File::where('slug', $slug)->firstOrFail();

        return view('site.show', compact('file'));
    }
}
