<?php

namespace App\Http\Controllers;

use App\Models\File;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Price;
use App\Models\Setting;

class FileSynchronizationController extends Controller
{
    public function synchronizeFiles()
    {
        $stripeSecretKey = Setting::where('key', 'stripe_secret_key')->value('value');
        Stripe::setApiKey($stripeSecretKey);

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
            // Проверяем, есть ли id у товара в бд
            if ($file->stripe_product_id) {
                // Проверка, если есть id у товара, но его нет в Stripe
                try {
                    $stripeProduct = Product::retrieve($file->stripe_product_id);
                } catch (\Exception $e) {
                    // Обнуляем id если товара нет в страйп
                    $file->stripe_product_id = null;
                    $file->save();

                    // создаем продукт и добавляем в список
                    $stripeProduct = $this->createStripeProduct($file);
                    $addedFiles[] = $file;
                    continue;
                }

                // если товар есть обновляем или скипаем
                if ($this->checkProduct($stripeProduct, $file)) {
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

    private function checkProduct($stripeProduct, $file)
    {
        $checkName = $file->name != $stripeProduct->name;
        $checkDesc = $file->description != $stripeProduct->description;

        // Находим id цены и цену
        $priceId = $stripeProduct->default_price;
        $priceAmount = Price::retrieve($priceId, [])->unit_amount;

        $checkPrice = $priceAmount != ($file->price * 100);

        return $checkName || $checkDesc || $checkPrice;
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

        $priceAmount = Price::retrieve($stripeProduct->default_price, [])->unit_amount;

        // Обновляем цену только если ее обновили
        if ($priceAmount != (int)$file->price * 100) {
            $newPrice = Price::create([
                'product' => $stripeProduct->id,
                'unit_amount' => $file->price * 100,
                'currency' => 'usd',
            ]);

            // добавляем цену в продукт
            Product::update(
                $stripeProduct->id,
                ['default_price' => $newPrice->id]
            );
        }
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
