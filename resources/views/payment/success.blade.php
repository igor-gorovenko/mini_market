@extends('layouts.app')

@section('content')

<h1>Success</h1>
<div>
    <a href="{{ route('site.index') }}" class="btn btn-primary mb-3">Go to index page</a>
</div>

<div>
    <a href="{{ route('site.show', ['slug' => $file->slug]) }}" class="btn btn-success mb-3">Go to {{ $file->name }}</a>
</div>

@endsection