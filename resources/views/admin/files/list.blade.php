@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('admin.index') }}">Back</a>
</div>
<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>List files</h1>
        <a href="{{ route('admin.files.create') }}" class="btn btn-outline-primary">Create New File</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Thumbnail</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Dates</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($files as $file)
        <tr>
            <td>{{ $file->id }}</td>
            <td>
                <img src="{{ asset('storage/' . $file->thumbnail) }}" width='50px' alt="Image">
            </td>
            <td><a href="{{ route('admin.files.show', ['name' => $file->name]) }}">{{ $file->name }}</a></td>
            <td>{{ $file->description }}</td>
            <td>${{ $file->price }}</td>
            <td>{{ $file->dates }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection