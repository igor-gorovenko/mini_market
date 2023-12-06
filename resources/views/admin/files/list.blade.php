@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('admin.index') }}">Back</a>
</div>
<h1>List files</h1>
<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Thumbnail</th>
            <th>Price</th>
            <th>Dates</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($files as $file)
        <tr>
            <td>{{ $file->id }}</td>
            <td><a href="{{ route('admin.files.show', ['name' => $file->name]) }}">{{ $file->name }}</a></td>
            <td>{{ $file->description }}</td>
            <td>
                @if($file->thumbnail)
                <img src="{{ asset('/storage/uploaded_files/images/' . pathinfo($file->path, PATHINFO_FILENAME) . '.jpg') }}" width='50px' alt="Image">
                @else
                No Thumbnail
                @endif
            </td>
            <td>${{ $file->price }}</td>
            <td>{{ $file->dates }}</td>
        </tr>
        @endforeach
    </tbody>
</table>


@endsection