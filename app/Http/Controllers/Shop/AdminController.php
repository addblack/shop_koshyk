<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function orders(Request $request)
    {
        $query = Order::with(['items', 'user'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        $counts = [
            'all'        => Order::count(),
            'new'        => Order::where('status', 'new')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'delivered'  => Order::where('status', 'delivered')->count(),
            'cancelled'  => Order::where('status', 'cancelled')->count(),
        ];

        return view('shop.admin.orders', compact('orders', 'counts'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:new,processing,delivered,cancelled',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', "Статус замовлення #{$order->id} оновлено.");
    }
}
