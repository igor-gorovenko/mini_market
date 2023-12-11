@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('site.index') }}">Back</a>
</div>
<div class="p-4 d-flex">
    <div class="container w-70">
        <h5>Preview</h5>
        <img src="{{ asset('storage/' . $file->thumbnail) }}" width='240px' height='240px' alt="image">
    </div>
    <div class="container w-30">
        <h3>{{ $file->name }}</h3>
        <div>Description: {{ $file->description }}</div>
        <div>Date: {{ $file->dates }}</div>
        <div>Price: ${{ $file->price }}</div>
        <div>
            <a href="{{ asset('/storage/' . $file->path) }}" download="{{ $file->name }}" class="btn btn-primary">Download {{ $file->name }}.pdf</a>
        </div>
    </div>
</div>

@endsection