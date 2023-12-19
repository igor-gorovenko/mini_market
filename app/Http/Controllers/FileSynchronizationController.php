<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;

class FileSynchronizationController extends Controller
{
    public function synchronizeFiles()
    {
        stripe::setApiKey(config('services.stripe.secret'));

        $files = File::all();
        $addedFiles = [];
        $updatedFiles = [];

        foreach ($files as $file) {
            // Проверяем, существует ли товар в Stripe
            if ($file->stripe_product_id) {
                $stripeProduct = Product::retrieve($file->stripe_product_id);

                // Обновляем товар            
                if ($this->shouldUpdateProduct($stripeProduct, $file)) {
                    $stripeProduct = $this->updateStripeProduct($stripeProduct, $file);
                    $updatedFiles[] = $file;
                }
            } else {
                // Если товара нет, создаем товар
                $stripeProduct = $this->createStripeProduct($file);
                $addedFiles[] = $file;
            }
        }

        return view('admin.files.sync-success', compact('addedFiles', 'updatedFiles'));
    }

    public function syncSuccess()
    {
        return view('admin.files.sync-success');
    }

    private function shouldUpdateProduct($file, $stripeProduct)
    {
        $nameDifference = $file->name !== $stripeProduct->name;
        $descriptionDifference = $file->description !== $stripeProduct->description;
        $priceDifference = ($file->price * 100) !== $stripeProduct->default_price;

        return $nameDifference || $descriptionDifference || $priceDifference;
    }

    private function updateStripeProduct($stripeProduct, $file)
    {

        Product::update(
            $stripeProduct->id,
            [
                'name' => $file->name,
                'description' => $file->description,
            ]
        );
    }

    private function createStripeProduct($file)
    {
        $stripeProduct = Product::create([
            'name' => $file->name,
            'type' => 'good',
            'description' => $file->description,
        ]);

        $price = Price::create([
            'product' => $stripeProduct->id,
            'unit_amount' => $file->price * 100,
            'currency' => 'usd',
        ]);

        // добавляем цену в продукт
        Product::update(
            $stripeProduct->id,
            ['default_price' => $price->id]
        );

        // Обновляем stripe_product_id в базе данных
        $file->stripe_product_id = $stripeProduct->id;
        $file->save();
    }
}
