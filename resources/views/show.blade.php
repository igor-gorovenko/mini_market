@extends('layouts.app')

@section('content')
<div>
    <a href="/" class="btn btn-secondary">Back</a>
</div>
<div class="p-4 d-flex">



    <div class="container w-70">
        <h5>Preview</h5>
        <iframe src="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" class="w-100 h-100"></iframe>

    </div>
    <div class="container w-30">

        <h3>{{ $file->name }}</h3>

        <div>Description: {{ $file->description }}</div>
        <div>Date: {{ $file->dates }}</div>
        <div>Price: ${{ $file->price }}</div>
        <div>
            <h5>Download</h5>
            {{-- Link to download the file --}}
            <a href="{{ asset('/storage/uploaded_files/' . basename($file->path)) }}" download="{{ $file->name }}" class="btn btn-primary">Download {{ $file->name }}.pdf</a>
        </div>

    </div>

</div>



@endsection