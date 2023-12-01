@extends('layouts.app')

@section('content')

<a href="/">Back</a>
<h3>Show File: {{ $file->name }}</h3>

<div>Name: {{ $file->name }}</div>
<div>description: {{ $file->description }}</div>

<iframe src="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" width="400"></iframe>
<div>src: {{asset('/storage/uploaded_files/' . basename($file->path)) }}</div>

<div>thumbnail: {{ $file->thumbnail }}</div>

<div>path: {{ $file->path }}</div>
<div>price: {{ $file->price }}</div>
<div>Dates: {{ $file->dates }}</div>
<div>Created: {{ $file->created_at }}</div>

<div>
    {{-- Link to download the file --}}
    <a href="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" download="{{ $file->name }}">Download File</a>
</div>


@endsection