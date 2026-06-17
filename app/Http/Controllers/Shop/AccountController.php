<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function orders()
    {
        $orders = Auth::user()
            ->orders()
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('shop.account.orders', compact('orders'));
    }
}
