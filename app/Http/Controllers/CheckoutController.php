<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout/checkout');
    }

    public function processPayment(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            // Создайте PaymentIntent с суммой, которую вы хотите счетать от пользователя
            $paymentIntent = PaymentIntent::create([
                'amount' => 1000, // Sum in cent now $10.00
                'currency' => 'usd',
                'payment_method' => $request->input('payment_method_id'),
                'confimation_method' => 'manual',
                'confirm' => true,
            ]);

            // Возвращаем ответ клиенту с client_secret для завершения оплаты на стороне фронтенда
            return response()->json(['client_secret' => $paymentIntent->client_secret]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
