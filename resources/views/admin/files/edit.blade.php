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

                    <form action="{{ route('admin.files.update', ['name' => $file->name]) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $file->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" class="form-control">{{ old('description', $file->description) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price', $file->price) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="dates">Dates:</label>
                            <input type="text" name="dates" class="form-control" value="{{ old('dates', $file->dates) }}">
                        </div>

                        <!-- Загрузка файлов -->
                        <h3>Downloads:</h3>
                        <div class="form-group">
                            <label for="thumbnail">Thumbnail:</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="path">PDF:</label>
                            <input type="file" name="path" id="path" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">Update File</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection