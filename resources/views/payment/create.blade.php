@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Create Payment</h1>

    <!-- Форма оплаты -->
    <form action="{{ route('payment.process') }}" method="post" id="payment-form">
        @csrf
        <label for="card-element">
            Card details
        </label>
        <div id="card-element">
            <!-- Карта оплаты -->
        </div>

        <!-- Добавьте поле для отображения ошибок -->
        <div id="card-errors" role="alert"></div>

        <button type="submit">Submit Payment</button>
    </form>
</div>

<!-- Включение Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Инициализация Stripe.js и создание элемента Card для ввода данных карты -->
<script>
    var stripe = Stripe('{{ config("services.stripe.key") }}');
    var elements = stripe.elements();

    // Создание элемента Card.
    var card = elements.create('card', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
            },
        },
        hidePostalCode: true,
    });

    // Добавление элемента Card к элементу с id="card-element".
    card.mount('#card-element');

    // Добавление обработчика событий для отображения ошибок.
    card.addEventListener('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    // Отправка формы оплаты при сабмите.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Отображение ошибок, если они есть.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Отправка токена Stripe на ваш сервер.
                stripeTokenHandler(result.token);
            }
        });
    });

    // Функция обработки токена Stripe на вашем сервере.
    function stripeTokenHandler(token) {
        // Добавьте логику для отправки токена на ваш сервер и обработки оплаты.
        // Например, вы можете использовать AJAX запрос для отправки токена на ваш сервер.
        console.log(token);
    }
</script>

@endsection