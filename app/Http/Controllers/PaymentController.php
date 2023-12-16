<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\File;

class PaymentController extends Controller
{
    public function createSession(Request $request, $name)
    {
        stripe::setApiKey(config('services.stripe.secret'));

        // Product stripe
        $productId = 'prod_PCKcHVp09WGaWp';

        // Устанавливаем желаемую стоимость
        $desireUnitAmount = 6000;

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


    private function getPriceId($productId, $desireUnitAmount)
    {
        $existingPrice = $this->findExistingPrice($productId, $desireUnitAmount);

        // Если цена есть, она используется
        if ($existingPrice) {
            return $existingPrice->id;
        } else {
            // иначе, создаем новую цену
            $product = \Stripe\Product::retrieve($productId);

            $newPrice = \Stripe\Price::create([
                'product' => $product->id,
                'unit_amount' => $desireUnitAmount,
                'currency' => 'usd',
            ]);

            return $newPrice->id;
        }
    }

    private function findExistingPrice($productId, $desireUnitAmount)
    {
        $existingPrices = \Stripe\Price::all([
            'product' => $productId,
            'unit_amount' => $desireUnitAmount,
            'currency' => 'usd',
        ]);

        $existingPrice = count($existingPrices->data) > 0 ? $existingPrices->data[0] : null;
        return $existingPrice;
    }
}
