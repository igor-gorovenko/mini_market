@extends('layouts.app')

@section('content')

<a href="/">Back</a>
<h3>Show File: {{ $file->name }}</h3>

<div>Name: {{ $file->name }}</div>
<div>description: {{ $file->description }}</div>
<div>Image: <img src="{{ asset($file->thumbnail) }}" alt="Thumbnail"></div>
<div>path: {{ $file->path }}</div>
<div>price: {{ $file->price }}</div>
<div>
    Dates: {{ $file->dates }}
</div>
<div>
    Created: {{ $file->created_at }}
</div>

<div>
    {{-- Link to download the file --}}
    <a href="{{ asset($file->path) }}" download="{{ $file->name }}">Download File</a>
</div>


@endsection