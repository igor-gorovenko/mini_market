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
    <div>Admin: {{ $user->is_admin == 1 ? 'Yes' : 'No' }}</div>


</div>

@endsection