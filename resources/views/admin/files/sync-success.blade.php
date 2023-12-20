@extends('layouts.app')

@section('content')


<div>
    <a href="{{ route('admin.files.list') }}" type="submit" class="btn btn-outline-primary">go to all files</a>
</div>

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Sync Success</h1>
    </div>
    <p>Congratulations! Your files have been synchronized successfully.</p>

    <h3>Result</h3>
    <table class="table table-bordered">
        <tr>
            <td>Added</td>
            <td>
                <ul>
                    @foreach($addedFiles as $file)
                    <li>{{ $file->name }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td>Updated</td>
            <td>
                <ul>
                    @foreach($updatedFiles as $file)
                    <li>{{ $file->name }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td>Skipped</td>
            <td>
                <ul>
                    @foreach($skippedFiles as $file)
                    <li>{{ $file->name }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>

    </table>


</div>


</div>



@endsection