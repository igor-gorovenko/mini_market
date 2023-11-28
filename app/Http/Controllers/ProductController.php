<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('show', compact('product'));
    }
}
