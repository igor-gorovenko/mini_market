<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use Stripe\Checkout\Session;
use App\Models\File;


class PaymentController extends Controller
{
    public function createSession(Request $request, $slug)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $file = File::where('slug', $slug)->first();

        // Донат
        $donateId = 'price_1OPM3MIFHAOiXzuRxZjkIo4D';
        $totalAmount = ($request->input('amount'));
        $donateQty = $totalAmount - $file->price;

        // Получаем ID продукта и цены
        $productId = $this->getProductId($file);
        $priceId = $this->getPriceId($productId);

        // Генерируем lineItems
        $lineItems = $this->generateLineItems($priceId, $donateId, $donateQty);

        $session = Session::create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('payment.success', ['slug' => $slug]),
            'cancel_url' => route('payment.cancel'),
        ]);

        return redirect($session->url);
    }

    public function successSession($slug)
    {
        $file = File::where('slug', $slug)->first();

        if ($file) {
            $file->update(['payment_status' => 'paid']);
        }

        return view('payment.success', compact('file'));
    }

    public function cancelSession()
    {
        return view('payment.cancel');
    }

    private function getProductId($file)
    {
        // Получаем все продукты stripe
        $allProducts = Product::all()->data;

        // Ищем продукт с нужным именем
        $foundProduct = null;
        foreach ($allProducts as $product) {
            if ($product->name == $file->name) {
                $foundProduct = $product;
                break;
            }
        }

        if (!$foundProduct) {
            throw new \Exception("Product ID is not found: $file->name");
        }

        return $foundProduct->id;
    }

    private function getPriceId($productId)
    {
        $price = Price::all([
            'product' => $productId,
            'currency' => 'usd',
        ]);

        if (empty($price->data) || !isset($price->data[0])) {
            throw new \Exception("Price ID is not found for product ID: $productId");
        }

        // Вернуть ID цены
        return $price->data[0]->id;
    }

    private function generateLineItems($priceId, $donateId, $donateQty)
    {
        $lineItems = [
            [
                'price' => $priceId,
                'quantity' => 1,
            ],
        ];

        // Добавляем донат, только если больше 0
        if ($donateQty > 0) {
            $lineItems[] = [
                'price' => $donateId,
                'quantity' => $donateQty,
            ];
        }

        return $lineItems;
    }
}
