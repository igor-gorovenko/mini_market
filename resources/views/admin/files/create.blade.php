@extends('layouts.app')

@section('content')

<h1>Create File</h1>
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
                    <form action="{{ route('admin.files.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Description:</label>
                            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price">Price:</label>
                            <input type="number" name="price" class="form-control" value="{{ old('price') }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="dates">Dates:</label>
                            <input type="text" name="dates" class="form-control" value="{{ old('dates') }}">
                        </div>

                        <!-- Загрузка файлов -->
                        <h3>Downloads:</h3>
                        <div class="form-group mb-3">
                            <label for="thumbnail">Thumbnail:</label>
                            <input type="file" name="thumbnail" id="thumbnail" class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="path">PDF:</label>
                            <input type="file" name="path" id="path" class="form-control">
                        </div>
                        <div>
                            <a href="{{ route('admin.files.list') }}" type="submit" class="btn btn-outline-primary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Create File</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection