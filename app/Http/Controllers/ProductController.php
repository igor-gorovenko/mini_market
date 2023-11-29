<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class ProductController extends Controller
{
    public function index()
    {
        $files = File::all();

        return view('index', ['files' => $files]);
    }

    public function show($id)
    {
        $file = File::findOrFail($id);

        return view('show', compact('file'));
    }
}
