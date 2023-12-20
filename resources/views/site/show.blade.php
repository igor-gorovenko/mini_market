@extends('layouts.app')

@section('content')
<script src="https://js.stripe.com/v3/"></script>

<div>
    <div class="mb-3">
        <a href="{{ route('site.index') }}" class="link-offset-2 link-underline link-underline-opacity-25">
            back to catalog
        </a>
    </div>
    <h1>Show page</h1>
</div>

<div class="d-flex">
    <div class="col-6 d-flex align-items-center bg-light p-3 text-center">
        <img src="{{ asset('storage/' . $file->thumbnail) }}" class="img-fluid mx-auto d-block" alt="image">
    </div>
    <div class="col-6 p-3">
        <!-- File info -->
        <h3>Details</h3>
        <table class="table table-bordered">
            <tr>
                <td>Title</td>
                <td>{{ $file->name }}</td>
            </tr>
            <tr>
                <td>Description</td>
                <td>{{ $file->description }}</td>
            </tr>
            <tr>
                <td>Date</td>
                <td>{{ $file->dates }}</td>
            </tr>
            <tr>
                <td>Price</td>
                <td>${{ $file->price }}</td>
            </tr>
        </table>
        <!-- Checkout -->
        <h3>Checkout</h3>

        <div class="mb-3">
            <form action="{{ route('payment.create', ['slug' => $file->slug]) }}" method="POST">
                @csrf
                <label for="amount" class="mr-2">Enter Amount:</label>
                <input type="number" name="amount" id="amount" min="{{ $file->price }}" value="{{ $file->price }}" step="1" required>
                <button type="submit" id="checkout-button" class="btn btn-success">Checkout</button>
            </form>
        </div>
        @if ($file->isPaid() || $file->price == 0)
        <!-- Download -->
        <h3>Download</h3>

        <div class="mb-3">
            <a href="{{ asset('/storage/' . $file->path) }}" download="{{ $file->name }}" class="btn btn-primary">Download {{ $file->name }}.pdf</a>
        </div>
        @endif
    </div>

</div>
</div>

@endsection