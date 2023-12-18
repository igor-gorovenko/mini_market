@extends('layouts.app')

@section('content')

<h1>Catalog {{ count($files) }}</h1>

<div class="row">
    @foreach ($files as $file)
    <div class="col-md-4 mb-4" class="">
        <div class="card bg-white border rounded-2">
            <img src="{{ asset('storage/' . $file->thumbnail) }}" class="card-img-top mx-auto" height='240px' alt="image">
            <div class="card-body">
                <h3>{{ $file->name }}</h3>
                <a href="{{ route('site.show', ['slug' => $file->slug]) }}" class="btn btn-outline-primary">
                    View
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection