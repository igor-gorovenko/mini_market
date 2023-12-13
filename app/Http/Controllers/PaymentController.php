<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        // Устанавливаем секретный ключ Stripe перед использованием
        Stripe::setApiKey(config('services.stripe.secret'));

        $slug = $request->input('slug');
        $file = File::where('slug', $slug)->first();

        if (!$file) {
            abort(404);
        }

        $priceId = 'price_1OMwpKIFHAOiXzuRtAlpoHNL';

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['slug' => $file->slug]),
            'cancel_url' => route('payment.cancel', ['slug' => $file->slug]),
        ]);

        return view('payment.create', ['sessionId' => $session->id]);
    }

    public function process(Request $request)
    {
        return redirect()->route('payment.success', ['slug' => $request->input('slug')]);
    }

    public function success($slug)
    {
        $file = File::where('slug', $slug)->first();

        if (!$file) {
            abort(404);
        }

        return view('payment.success', compact('file'));
    }

    public function cancel($slug)
    {
        $file = File::where('slug', $slug)->first();

        if (!$file) {
            abort(404);
        }

        return view('payment.cancel', compact('file'));
    }
}
