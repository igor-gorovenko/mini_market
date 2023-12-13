<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('payment/checkout');
    }

    public function processPayment(Request $request)
    {
        // Установка секретного ключа Stripe
        Stripe::setApiKey(config('services.stripe.secret'));

        // Получение данных из формы
        $token = $request->input('stripeToken');
        $amount = 1000; // Замените эту сумму на фактическую сумму заказа в центах

        // Создание платежа
        try {
            Charge::create([
                'amount' => $amount,
                'currency' => 'usd',
                'source' => $token,
            ]);

            return view('payment.success');
        } catch (\Exception $e) {
            return view('payment.error');
        }
    }
}
