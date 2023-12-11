@extends('layouts.app')

@section('content')

<h1>Catalog</h1>

<div class="row">
    @foreach ($files as $file)
    <div class="col-md-4 mb-4">
        <div class="card border-0 bg-white">
            <img src="{{ asset('storage/' . $file->thumbnail) }}" class="card-img-top mx-auto" height='240px' alt="image">
            <div class="card-body text-center">
                <a href="{{ route('user.show', ['slug' => $file->slug]) }}" class="btn btn-outline-primary">
                    {{ $file->name }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection