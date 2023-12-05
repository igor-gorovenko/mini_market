@extends('layouts.app')

@section('content')

<h1>Catalog</h1>

<ul>
    @foreach ($files as $file)
    <li><a href="{{ route('show', ['name' => $file->name]) }}">{{ $file->name }}</a></li>
    @endforeach
</ul>

@endsection