<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    //
    public function orders()
    {
        // Логика для отображения заказов пользователя
        // Например, получение заказов из базы данных
        $orders = auth()->user()->orders; // Пример получения заказов для текущего пользователя

        return view('user.orders', compact('orders'));
    }

    public function address()
    {
        // Получаем все адреса текущего пользователя (пример)
        $addresses = auth()->user()->addresses;

        // Возвращаем представление с адресами
        return view('user.address', compact('addresses'));
    }
}
