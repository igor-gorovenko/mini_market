@extends('layouts.app')

@section('content')

<ul>
    @foreach ($files as $file)
    <div>
        <a href="{{ route('admin.files.edit', ['name' => $file->name]) }}">{{ $file->name }}</a>
    </div>
    @endforeach
</ul>

@endsection