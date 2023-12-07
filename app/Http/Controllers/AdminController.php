<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\File;
use App\Models\User;

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

    public function delete($name)
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

            $thumbnailPath = $request->file('thumbnail')->storeAs('uploaded_files/images', $request->input('name') . '.jpg', 'public');
            $file->update(['thumbnail' => $thumbnailPath]);
        }

        // Обработка загрузки нового PDF-файла (если был выбран новый)
        if ($request->hasFile('path')) {
            // Удаление предыдущего PDF-файла (если оно есть)
            if ($file->path) {
                Storage::disk('public')->delete($file->path);
            }

            // Сохранение нового PDF-файла
            $pdfPath = $request->file('path')->storeAs('uploaded_files/pdf', $request->input('name') . '.pdf', 'public');
            $file->update(['path' => $pdfPath]);
        }
    }

    // Users

    public function users()
    {
        $users = User::all();

        return view('admin.users.list', compact('users'));
    }

    public function showUser($name)
    {
        $user = User::where('name', $name)->first();

        if (!$user) {
            abort(404);
        }

        return view('admin.users.show', compact('user'));
    }
}
