@extends('layouts.app')

@section('content')

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Users ({{ count($users) }})</h1>
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
            <th>Role</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @php $index = 1 @endphp
        @foreach ($users as $user)

        <tr class="align-middle">
            <td>{{ $index++ }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->updated_at }}</td>
            <td>{{ $user->is_admin == 1 ? 'Admin' : 'User' }}</td>
            <td>
                <div class="d-flex">
                    <a href="{{ route('admin.users.edit', ['slug' => $user->slug]) }}" title="edit" class="btn btn-outline-primary btn-sm mx-1">Edit</a>
                    <a href="{{ route('admin.users.destroy', ['slug' => $user->slug]) }}" title="delete" class="btn btn-outline-danger btn-sm mx-1">Delete</a>
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection