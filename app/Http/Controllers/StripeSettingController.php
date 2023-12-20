<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;


class StripeSettingController extends Controller
{
    public function index()
    {
        // Получаем текущие значения ключей Stripe из базы данных
        $stripePublicKey = Setting::where('key', 'stripe_public_key')->value('value');
        $stripeSecretKey = Setting::where('key', 'stripe_secret_key')->value('value');

        return view('admin.settings.index', compact('stripePublicKey', 'stripeSecretKey'));
    }

    public function update(Request $request)
    {
        // Валидация данных
        $request->validate([
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
        ]);

        // Сохранение данных в базу данных
        Setting::updateOrCreate(['key' => 'stripe_public_key'], ['value' => $request->input('public_key')]);
        Setting::updateOrCreate(['key' => 'stripe_secret_key'], ['value' => $request->input('secret_key')]);

        // Редирект с сообщением об успешном сохранении
        return redirect()->route('admin.settings.index')->with('success', 'Stripe settings updated successfully');
    }
}
