@extends('layouts.app')

@section('content')

<style>
    .container {
        border: 8px;
        padding: 10px;
    }

    .preview-container {
        width: 50%;
    }

    .info-container {
        width: 50%;
    }
</style>

<div style="display: flex;">
    <div>
        <a href="/">Back</a>
    </div>


    <div class="container preview-container">
        <div>
            <h5>Preview</h5>
            <iframe src="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" style="width: 100%; aspect-ratio: attr(width) / attr(height);"></iframe>
        </div>
    </div>
    <div class="container info-container">
        <h3>{{ $file->name }}</h3>

        <div>Description: {{ $file->description }}</div>

        <div>Date: {{ $file->dates }}</div>
        <div>Price: ${{ $file->price }}</div>
        <div>
            <h5>Download</h5>
            {{-- Link to download the file --}}
            <a href="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" download="{{ $file->name }}">Download {{ $file->name }}.pdf</a>
        </div>
    </div>

</div>



@endsection