@extends('layouts.app')

@section('content')

<a href="/">Back</a>
<h3>{{ $file->name }} ({{ $file->dates }})</h3>

<div>Description: {{ $file->description }}</div>

<div>Date: {{ $file->dates }}</div>
<div>Price: ${{ $file->price }}</div>
<div>
    <h5>Preview</h5>
    <iframe src="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" width="400"></iframe>
</div>


<div>
    <h5>Download</h5>
    {{-- Link to download the file --}}
    <a href="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" download="{{ $file->name }}">Download {{ $file->name }}.pdf</a>
</div>


@endsection