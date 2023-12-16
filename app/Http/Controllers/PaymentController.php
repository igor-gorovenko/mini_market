<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function createSession()
    {
        stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'line_items' => [[
                'price' => 'price_1OMwpKIFHAOiXzuRtAlpoHNL',
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
        ]);

        return response()->json(['id' => $session->id]);
    }

    public function successSession()
    {
        return view('payment.success');
    }
}
