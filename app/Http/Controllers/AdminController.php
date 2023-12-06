<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
    }

    // Files
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
        return view('admin.files.create');
    }

    public function edit(Request $request, $name)
    {
        $file = File::where('name', $name)->firstOrFail();

        // Проверка, была ли отправлена форма редактирования
        if (request()->isMethod('post')) {
            $this->validateAndSaveFile($request, $file);
            return redirect()->route('admin.files.show', ['name' => $file->name])->with('success', 'File updated');
        }

        return view('admin.files.edit', compact('file'));
    }

    public function update(Request $request, $name)
    {
        $file = File::where('name', $name)->firstOrFail();
        $this->validateAndSaveFile($request, $file);
        return redirect()->route('admin.files.show', ['name' => $file->name])->with('success', 'File updated');
    }

    private function validateAndSaveFile(Request $request, File $file)
    {
        // Валидация данных
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'nullable',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'path' => 'required',
            'price' => 'required|numeric',
            'dates' => 'nullable|date',
        ]);

        // Обновление данных в базе
        $file->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'dates' => $request->input('dates'),
        ]);

        // Обработка загрузки нового изображения (если было выбрано новое)
        if ($request->hasFile('thumbnail')) {
            // Удаление предыдущего изображения (если оно есть)
            if ($file->thumbnail) {
                Storage::disk('public')->delete($file->thumbnail);
            }


            $thumbnailPath = $request->file('thumbnail')->storeAs('uploaded_files/images', $file->name . '.jpg', 'public');
            $file->update(['thumbnail' => $thumbnailPath]);
        }
    }

    public function delete($name)
    {
        $file = File::where('name', $name)->firstOrFail();

        return view('admin.files.delete', compact('file'));
    }
}
