@extends('layouts.app')

@section('content')


<div>
    <a href="{{ route('admin.files.list') }}" type="submit" class="btn btn-outline-primary">go to all files</a>
</div>

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Sync Success</h1>



    </div>
    <h2>Added Files:</h2>
    <ul>
        @foreach($addedFiles as $file)
        <li>{{ $file->name }}</li>
        @endforeach
    </ul>

    <h2>Updated Files:</h2>
    <ul>
        @foreach($updatedFiles as $file)
        <li>{{ $file->name }}</li>
        @endforeach
    </ul>

    <p>Congratulations! Your files have been synchronized successfully.</p>
</div>



@endsection