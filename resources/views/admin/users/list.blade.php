@extends('layouts.app')

@section('content')

<div>
    <a href="{{ route('admin.index') }}">Back</a>
</div>

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>List users</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-primary">Create New User</a>
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Updated</th>
            <th>Admin</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td><a href="{{ route('admin.users.show', ['id' => $user->id]) }}">{{ $user->name }}</a></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->updated_at }}</td>
            <td>{{ $user->is_admin == 1 ? 'Yes' : 'No' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection