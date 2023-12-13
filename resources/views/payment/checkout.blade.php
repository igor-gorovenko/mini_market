@extends('layouts.app')

@section('content')

<div class="container">
    <h1 class="mt-5 mb-4">Checkout</h1>

    <!-- Форма оплаты -->
    <form action="/checkout" method="post">
        @csrf
        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button" data-key="{{ config('services.stripe.key') }}" data-amount="1000" data-name="Ваш магазин" data-description="Описание вашего заказа" data-image="https://example.com/logo.png" data-locale="auto" data-currency="usd">
        </script>
    </form>
</div>
@endsection