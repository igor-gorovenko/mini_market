<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentController extends Controller
{
    public function createSession(Request $request)
    {
        stripe::setApiKey(config('services.stripe.secret'));

        $minimumAmount = 1000;
        $amount = $request->input('amount') ?: $minimumAmount;

        $product = \Stripe\Product::create([
            'name' => 'Product test 1 ',
            'description' => 'test desc 1',
        ]);

        $price = \Stripe\Price::create([
            'product' => $product->id,
            'unit_amount' => 1000,
            'currency' => 'usd',
        ]);

        $session = Session::create([
            'line_items' => [[
                'price' => $price->id,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

        // return response()->json(['id' => $session->id]);
        return redirect($session->url);
    }

    public function successSession()
    {
        return view('payment.success');
    }

    public function cancelSession()
    {
        return view('payment.cancel');
    }
}
