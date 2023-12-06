@extends('layouts.app')

@section('content')
<div>
    <a href="{{ route('admin.files.show', ['name' => $file->name]) }}">Back</a>
</div>
<h1>Edit page</h1>
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-body">
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <form action="{{ route('admin.files.edit', ['name' => $file->name]) }}" method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('POST') }}

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $file->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" class="form-control">{{ old('description', $file->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Thumbnail Preview:</label>
                            @if($file->thumbnail)
                            <img src="{{ asset('/storage/uploaded_files/images/' . pathinfo($file->path, PATHINFO_FILENAME) . '.jpg') }}" width='50px' alt="Image">
                            @else
                            <p>No thumbnail available</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="thumbnail">Thumbnail:</label>
                            <input type="file" name="thumbnail" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $file->price) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="dates">Dates:</label>
                            <input type="text" name="dates" class="form-control" value="{{ old('dates', $file->dates) }}">
                        </div>

                        <div class="form-group">
                            <label for="path">Path:</label>
                            <input type="text" name="path" class="form-control" value="{{ old('path', $file->path) }}" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Update File</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection