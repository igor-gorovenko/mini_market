@extends('layouts.app')

@section('content')

<h1>Admin Dashboard</h1>

<div><a href="{{ route('admin.files.list') }}">Files</a></div>
<div>Users</div>
<div>Settings</div>

@endsection