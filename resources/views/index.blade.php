@extends('layouts.app')

@section('content')

<h1>Catalog</h1>

<ul>
    @foreach ($products as $product)
    <li><a href="{{ route('show', ['id' => $product->id]) }}">{{ $product->title }}</a></li>
    @endforeach
</ul>

@endsection