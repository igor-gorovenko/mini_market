@extends('layouts.app')

@section('content')

<!-- stripe scripts -->
<script src="https://js.stripe.com/v3/"></script>
<script async src="https://js.stripe.com/v3/buy-button.js"></script>

<div>
    <a href="{{ route('site.index') }}">Back</a>
</div>
<div class="p-4 d-flex">
    <div class="container w-70">
        <h5>Preview</h5>
        <img src="{{ asset('storage/' . $file->thumbnail) }}" width='240px' height='240px' alt="image">
    </div>
    <div class="container w-30">
        <h3>{{ $file->name }}</h3>
        <div class="mb-3">Description: {{ $file->description }}</div>
        <div class="mb-3">Date: {{ $file->dates }}</div>
        <div class="mb-3">Price: ${{ $file->price }}</div>
        <div class="mb-3">
            <a href="{{ asset('/storage/' . $file->path) }}" download="{{ $file->name }}" class="btn btn-primary">Download {{ $file->name }}.pdf</a>
        </div>
        <!-- stripe button -->
        <stripe-buy-button buy-button-id="buy_btn_1ONaLlIFHAOiXzuRDyvFjP5z" publishable-key="pk_test_51OMYxJIFHAOiXzuRkVhtQbKEmwxbMnH714cJGHb5gVAVA9DwOhRLKvoaXxke2mXJd1WLh0fZkFyjullPGqmj4Tdn00UEOS2neZ">
        </stripe-buy-button>
    </div>
</div>

@endsection