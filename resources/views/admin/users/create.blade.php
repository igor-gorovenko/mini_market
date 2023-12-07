@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('admin.users.list') }}">Back</a>
</div>

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Create New User</h1>

    </div>
</div>

<form action="{{ route('admin.users.store') }}" method="post">
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>

    <button type="submit" class="btn btn-primary">Create User</button>
</form>

@endsection