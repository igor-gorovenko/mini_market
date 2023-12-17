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
        stripe::setApiKey(config('services.stripe.secret'));

        $file = File::where('slug', $slug)->first();

        $defaultProductPrice = $file->price;
        $inputAmount = $request->input('amount');

        // Получаем стоимость товара
        $productPrice = $this->getProductPrice($defaultProductPrice, $inputAmount);

        // Найти продукты в базе
        $productId = $this->getProductId($file);

        // Получить ID цены
        $priceId = $this->getPriceId($productId, $productPrice);

        $session = Session::create([
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
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

        if ($foundProduct) {
            // Если продукт найден, возвращаем его id
            return $foundProduct->id;
        } else {

            $stripeProduct = Product::create([
                'name' => $file->name,
                'description' => $file->description,
            ]);

            return $stripeProduct->id;
        }
    }

    private function getPriceId($productId, $productPrice)
    {
        $price = Price::all([
            'product' => $productId,
            'unit_amount' => $productPrice,
            'currency' => 'usd',
        ]);

        if (count($price->data) > 0) {
            // Если цена уже существует, используйте ее
            return $price->data[0]->id;
        } else {
            // иначе, создаем новую цену
            $newPrice = Price::create([
                'product' => $productId,
                'unit_amount' => $productPrice,
                'currency' => 'usd',
            ]);

            return $newPrice->id;
        }
    }

    private function getProductPrice($productPrice, $inputAmount)
    {
        // Проверьте, что введенная сумма больше стоимости товара
        if ($productPrice <= $inputAmount) {
            $productPrice = $inputAmount;
        }

        // Исправляем цену продукта, в страйп без десятичных знаков
        $newProdictPrice = round($productPrice * 100);

        return $newProdictPrice;
    }
}
