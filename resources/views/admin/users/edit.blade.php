@extends('layouts.app')

@section('content')

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Edit User - {{ $user->name }}</h1>
    </div>
</div>

<form action="{{ route('admin.users.update', ['slug' => $user->slug]) }}" method="post">
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

    <div class="mb-3">
        <label class="form-label">Role:</label>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="is_admin" id="is_admin_yes" value="1" {{ $user->is_admin ? 'checked' : '' }}>
            <label class="form-check-label" for="is_admin_yes">
                Admin
            </label>
        </div>

        <div class="form-check">
            <input class="form-check-input" type="radio" name="is_admin" id="is_admin_no" value="0" {{ !$user->is_admin ? 'checked' : '' }}>
            <label class="form-check-label" for="is_admin_no">
                User
            </label>
        </div>
    </div>
    <div>
        <a href="{{ route('admin.users.list') }}" type="submit" class="btn btn-outline-primary">Cancel</a>
        <button type="submit" class="btn btn-primary">Update User</button>
    </div>
</form>

@endsection