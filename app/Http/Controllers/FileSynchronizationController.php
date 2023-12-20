<?php

namespace App\Http\Controllers;

use App\Models\File;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;


class FileSynchronizationController extends Controller
{
    public function synchronizeFiles()
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        // Проверяем есть ли товар extra, если нет создаем его
        $extraProduct = $this->findOrCreateExtraProduct();

        $files = File::all();

        // Списка для вывода в результате
        $addedFiles = [];
        $updatedFiles = [];
        $skippedFiles = [];

        // Если мы создали товар extra = добавляем его в список
        if ($extraProduct !== null) {
            $addedFiles[] = $extraProduct;
        }

        foreach ($files as $file) {
            // Проверяем, существует ли товар в Stripe
            if ($file->stripe_product_id) {
                $stripeProduct = Product::retrieve($file->stripe_product_id);

                // Проверяем наличие изменений в файле
                if ($this->shouldUpdateProduct($stripeProduct, $file)) {
                    $stripeProduct = $this->updateStripeProduct($stripeProduct, $file);
                    $updatedFiles[] = $file;
                } else {
                    $skippedFiles[] = $file;
                }
            } else {
                // Если товара нет, создаем товар
                $stripeProduct = $this->createStripeProduct($file);
                $addedFiles[] = $file;
            }
        }

        return view('admin.files.sync-success', compact('addedFiles', 'updatedFiles', 'skippedFiles'));
    }

    function findOrCreateExtraProduct()
    {
        // Список товаров активных, не в архиве
        $allProducts = Product::all(['active' => true]);

        $searchedName = 'extra';

        // Поиск товара по имени
        foreach ($allProducts->data as $product) {
            if ($product->name == $searchedName) {
                return null;
            }
        }

        $extraProduct = null;
        // Если товара нет, создаем его
        if (!$extraProduct) {
            $stripeProduct = Product::create([
                'name' => 'extra',
                'type' => 'good',
                'description' => 'donate',
            ]);

            $price = Price::create([
                'product' => $stripeProduct->id,
                'unit_amount' => 100, // $1 = 100 cents
                'currency' => 'usd',
            ]);

            // добавляем цену в продукт
            Product::update(
                $stripeProduct->id,
                ['default_price' => $price->id]
            );

            return $stripeProduct;
        }
    }

    public function syncSuccess()
    {
        return view('admin.files.sync-success');
    }

    private function shouldUpdateProduct($file, $stripeProduct)
    {
        $nameDifference = $file->name != $stripeProduct->name;
        $descriptionDifference = $file->description != $stripeProduct->description;
        $priceDifference = ($file->price * 100) != $stripeProduct->default_price;

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
