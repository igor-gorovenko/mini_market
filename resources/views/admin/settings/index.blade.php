@extends('layouts.app')

@section('content')

<div class="mt-2 mb-2">
    <div class="d-flex justify-content-between align-items-center">
        <h1>Settings</h1>
    </div>
    <p>Keys stripe</p>
    <form action="{{ route('admin.settings.update') }}" method="post">
        @csrf

        <div class="mb-3">
            <label for="public_key" class="form-label">Public Key:</label>
            <input type="text" name="public_key" value="{{ $stripePublicKey }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="secret_key" class="form-label">Secret Key:</label>
            <input type="text" name="secret_key" value="{{ $stripeSecretKey }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</div>


</div>



@endsection