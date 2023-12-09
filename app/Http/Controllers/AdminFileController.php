<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use App\Models\File;

class AdminFileController extends Controller
{

    public function files()
    {
        $files = File::all();

        return view('admin.files.list', compact('files'));
    }

    public function show($slug)
    {
        $file = File::where('slug', $slug)->firstOrFail();

        if (!$file) {
            abort(404);
        }

        return view('admin.files.show', compact('file'));
    }

    public function create()
    {
        return view('admin.files.create');
    }

    public function store(Request $request)
    {
        // Валидация
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'dates' => 'nullable|date',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимальный размер 2Мб
            'path' => 'nullable|mimes:pdf|max:2048',
        ]);

        $file = File::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'dates' => $request->input('dates'),
            'slug' => Str::slug($request->input('name'), '-'),
        ]);

        $this->uploadFile($file, 'thumbnail', 'uploaded_files/images');
        $this->uploadFile($file, 'path', 'uploaded_files/pdf');

        return redirect()->route('admin.files.list')->with('success', 'File created');
    }


    public function edit($slug)
    {
        $file = File::where('slug', $slug)->first();

        if (!$file) {
            abort(404);
        }

        return view('admin.files.edit', compact('file'));
    }

    public function update(Request $request, $slug)
    {
        $file = File::where('slug', $slug)->first();

        // Валидация
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'dates' => 'nullable|date',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимальный размер 2Мб
            'path' => 'nullable|mimes:pdf|max:2048',
        ]);

        // Обновление данных в базе
        $file->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'dates' => $request->input('dates'),
            'slug' => Str::slug($request->input('name'), '-'),
        ]);

        $this->uploadFile($file, 'thumbnail', 'uploaded_files/images');
        $this->uploadFile($file, 'path', 'uploaded_files/pdf');

        return redirect()->route('admin.files.show', ['slug' => $file->slug])->with('success', 'File updated');
    }

    public function destroy($slug)
    {
        $file = File::where('slug', $slug)->firstOrFail();

        if ($file->thumbnail && Storage::disk('public')->exists($file->thumbnail)) {
            Storage::disk('public')->delete($file->thumbnail);
        }


        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        return redirect()->route('admin.files.list')->with('success', 'file deleted');
    }

    private function uploadFile($file, $fieldName, $storagePath)
    {
        if (request()->hasFile($fieldName)) {
            if ($file->$fieldName && Storage::disk('public')->exists($file->$fieldName)) {
                Storage::disk('public')->delete($file->$fieldName);
            }

            $filePath = request()->file($fieldName)->store($storagePath, 'public');
            $file->$fieldName = $filePath;
            $file->save();
        }
    }
}
