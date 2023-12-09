@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('admin.users.list') }}">Back</a>
</div>
<h1>Show user</h1>
<div class="p-4">

    <h3>{{ $user->name }}</h3>
    <div>Id: {{ $user->id }}</div>
    <div>Email: {{ $user->email }}</div>
    <div>Updated: {{ $user->updated_at }}</div>
    <div>Role: {{ $user->is_admin == 1 ? 'Admin' : 'User' }}</div>
    <div>
        <h3>Actions</h3>
        <a href="{{ route('admin.users.edit', ['slug' => $user->slug]) }}" class="btn btn-outline-primary">Edit</a>
        <a href="{{ route('admin.users.destroy', ['slug' => $user->slug]) }}" class="btn btn-outline-danger">Delete</a>
    </div>
</div>

@endsection