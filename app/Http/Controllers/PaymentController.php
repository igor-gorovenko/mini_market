<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Product;
use App\Models\File;

class PaymentController extends Controller
{
    public function createSession(Request $request, $name)
    {
        stripe::setApiKey(config('services.stripe.secret'));

        // Product stripe

        $productId = 'prod_PCKcHVp09WGaWp';

        // Устанавливаем стоимость
        $desireUnitAmount = 1300;

        // Получить ID цены
        $priceId = $this->getPriceId($productId, $desireUnitAmount);

        $session = Session::create([
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
        ]);

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

    private function getPriceId($productId, $unitAmount)
    {
        $price = \Stripe\Price::all([
            'product' => $productId,
            'unit_amount' => $unitAmount,
            'currency' => 'usd',
        ]);

        if (count($price->data) > 0) {
            // Если цена уже существует, используйте ее
            return $price->data[0]->id;
        } else {
            // иначе, создаем новую цену
            $newPrice = \Stripe\Price::create([
                'product' => $productId,
                'unit_amount' => $unitAmount,
                'currency' => 'usd',
            ]);

            return $newPrice->id;
        }
    }
}
