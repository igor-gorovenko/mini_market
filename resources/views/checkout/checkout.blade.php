@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mt-5 mb-4">Checkout</h1>

    <form action="{{ url('/checkout') }}" method="post" id="payment-form" class="needs-validation" novalidate>
        @csrf
        <div class="form-group">
            <label for="card-element" class="form-label">Card details</label>
            <div id="card-element" class="form-control"></div>
            <div id="card-errors" role="alert" class="invalid-feedback"></div>
        </div>
        <button id="submit" class="btn btn-primary mt-3">
            <div class="spinner hidden" id="spinner"></div>
            <span id="button-text">Pay now</span>
        </button>
        <p id="card-error" role="alert" class="mt-2"></p>
        <p class="result-message hidden mt-2">
            Payment succeeded, see the result in your
            <a href="" target="_blank">Stripe dashboard.</a>
            Refresh the page to pay again.
        </p>
    </form>
</div>

<script>
    const stripe = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const cardElement = elements.create('card');

    cardElement.mount('#card-element');

    const form = document.getElementById('payment-form');
    const button = document.getElementById('submit');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('button-text');
    const cardErrors = document.getElementById('card-errors');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        button.disabled = true;
        spinner.classList.remove('hidden');
        buttonText.textContent = 'Processing...';

        const {
            token,
            error
        } = await stripe.createToken(cardElement);

        if (error) {
            cardErrors.textContent = error.message;

            button.disabled = false;
            spinner.classList.add('hidden');
            buttonText.textContent = 'Pay now';
        } else {
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method_id');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            form.submit();
        }
    });
</script>

@endsection