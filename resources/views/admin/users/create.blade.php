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


@endsection