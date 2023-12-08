<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\File;


class AdminFileController extends Controller
{

    public function files()
    {
        $files = File::all();

        return view('admin.files.list', compact('files'));
    }

    public function show($name)
    {
        $file = File::where('name', $name)->firstOrFail();

        if (!$file) {
            abort(404);
        }

        return view('admin.files.show', compact('file'));
    }

    public function create()
    {
        return view('admin.fiels.create');
    }

    public function store()
    {
        //
    }


    public function edit($name)
    {
        $file = File::where('name', $name)->first();

        if (!$file) {
            abort(404);
        }

        return view('admin.files.edit', compact('file'));
    }

    public function update(Request $request, $name)
    {
        $file = File::where('name', $name)->first();

        // Валидация
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'price' => 'required|numeric',
            'dates' => 'nullable|date',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Максимальный размер 2Мб

        ]);

        // Обновление данных в базе
        $file->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'dates' => $request->input('dates'),
        ]);


        // Загрузка изображения, если оно было прикреплено
        if ($request->hasFile('thumbnail')) {
            // Предварительное удаление старого изображения (если не сделано выше)
            if ($file->thumbnail && Storage::disk('public')->exists($file->thumbnail)) {
                Storage::disk('public')->delete($file->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('uploaded_files/images', 'public');
            $file->thumbnail = $thumbnailPath;
            $file->save();
        }

        return redirect()->route('admin.files.show', ['name' => $file->name])->with('success', 'File updated');
    }

    public function destroy($name)
    {
        $file = File::where('name', $name)->firstOrFail();

        if ($file->thumbnail && Storage::disk('public')->exists($file->thumbnail)) {
            Storage::disk('public')->delete($file->thumbnail);
        }

        if ($file->path && Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        return redirect()->route('admin.files.list')->with('success', 'file deleted');
    }
}
