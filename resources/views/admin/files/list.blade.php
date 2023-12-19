@extends('layouts.app')

@section('content')

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Files ({{ count($files) }})</h1>
        <div class="d-flex">
            <!-- Добавим кнопку для синхронизации -->
            <form method="post" action="{{ route('admin.files.synchronize') }}">
                @csrf
                <button type="submit" class="btn btn-outline-success me-2">Sync with Stripe</button>
            </form>
            <a href="{{ route('admin.files.create') }}" class="btn btn-outline-primary">Create New File</a>
        </div>
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
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $index = 1 @endphp
        @foreach ($files as $file)
        <tr class="align-middle">
            <td>{{ $index++ }}</td>
            <td>
                <img src="{{ asset('storage/' . $file->thumbnail) }}" width='50px' alt="Image">
            </td>
            <td>{{ $file->name }}</td>
            <td>{{ $file->description }}</td>
            <td>${{ $file->price }}</td>
            <td>{{ $file->dates }}</td>
            <td>
                <div class="d-flex">
                    <a href="{{ route('site.show', ['slug' => $file->slug]) }}" target="_blank" title="view" class="btn btn-outline-primary btn-sm mx-1">View</a>
                    <a href="{{ route('admin.files.edit', ['slug' => $file->slug]) }}" title="edit" class="btn btn-outline-primary btn-sm mx-1">Edit</a>
                    <a href="{{ route('admin.files.destroy', ['slug' => $file->slug]) }}" title="delete" class="btn btn-outline-danger btn-sm mx-1">Delete</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection