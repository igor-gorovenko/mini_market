@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('admin.files.list') }}">Back</a>
</div>
<h1>Show page</h1>
<div class="p-4 d-flex">
    <div class="container w-70">
        <img src="{{ asset('storage/' . $file->thumbnail) }}" width='300px' alt="Image">
    </div>
    <div class="container w-30">
        <h3>{{ $file->name }}</h3>
        <div>Description: {{ $file->description }}</div>
        <div>Date: {{ $file->dates }}</div>
        <div>Price: ${{ $file->price }}</div>

        <div>
            <a href="{{ asset('storage/' . $file->path) }}" download="{{ $file->name }}" class="btn btn-primary">Download {{ $file->name }}.pdf</a>
        </div>

        <div>
            <h3>Actions</h3>
            <a href="{{ route('admin.files.edit', ['name' => $file->name]) }}" class="btn btn-outline-primary">Edit</a>
            <a href="{{ route('admin.files.destroy', ['name' => $file->name]) }}" class="btn btn-outline-danger">Delete</a>
        </div>
    </div>
</div>

@endsection