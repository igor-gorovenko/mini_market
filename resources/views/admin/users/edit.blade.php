@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('admin.users.list') }}">Back</a>
</div>

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit User - {{ $user->name }}</h1>
    </div>
</div>

<form action="{{ route('admin.users.update', ['name' => $user->name]) }}" method="post">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ $user->email }}">
    </div>

    <button type="submit" class="btn btn-primary">Save Changes</button>
</form>

@endsection