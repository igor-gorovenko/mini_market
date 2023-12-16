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
    public function createSession(Request $request)
    {
        stripe::setApiKey(config('services.stripe.secret'));

        $name = $request->input('file_name');
        // Найти продукты в базе
        $productId = $this->getProductId($name);

        // Устанавливаем стоимость
        $unitAmount = 3300;

        // Получить ID цены
        $priceId = $this->getPriceId($productId, $unitAmount);

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

    private function getProductId($productName)
    {
        // Получаем все продукты
        $allProducts = Product::all()->data;

        // Ищем продукт с нужным именем
        $foundProduct = null;
        foreach ($allProducts as $product) {
            if ($product->name == $productName) {
                $foundProduct = $product;
                break;
            }
        }

        if ($foundProduct) {
            // Если продукт найден, возвращаем его id
            return $foundProduct->id;
        } else {
            // Если продукт не найден, создаем новый в Stripe
            $stripeProduct = Product::create([
                'name' => $productName,
                'description' => 'Product description', // Замените на актуальное описание
                // Другие параметры продукта
            ]);

            return $stripeProduct->id;
        }
    }

    private function getPriceId($productId, $unitAmount)
    {
        $price = Price::all([
            'product' => $productId,
            'unit_amount' => $unitAmount,
            'currency' => 'usd',
        ]);

        if (count($price->data) > 0) {
            // Если цена уже существует, используйте ее
            return $price->data[0]->id;
        } else {
            // иначе, создаем новую цену
            $newPrice = Price::create([
                'product' => $productId,
                'unit_amount' => $unitAmount,
                'currency' => 'usd',
            ]);

            return $newPrice->id;
        }
    }
}
