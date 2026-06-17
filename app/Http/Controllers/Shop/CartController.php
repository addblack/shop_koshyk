<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    /** GET /cart */
    public function index()
    {
        $items = $this->cart->items();
        $total = $this->cart->total();
        return view('shop.cart', compact('items', 'total'));
    }

    /** POST /cart/add — AJAX */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'integer|min:1|max:99',
        ]);

        $item = $this->cart->add(
            $request->integer('product_id'),
            $request->integer('quantity', 1)
        );

        return response()->json([
            'success'    => true,
            'message'    => 'Товар додано до кошика',
            'cart_count' => $this->cart->totalQty(),
            'cart_total' => number_format($this->cart->total(), 2),
            'item' => [
                'id'       => $item->id,
                'qty'      => $item->quantity,
                'subtotal' => number_format($item->quantity * $item->product->price, 2),
            ],
        ]);
    }

    /** PATCH /cart/update — AJAX */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:0|max:99',
        ]);

        $item = $this->cart->update(
            $request->integer('product_id'),
            $request->integer('quantity')
        );

        return response()->json([
            'success'    => true,
            'removed'    => $item === null,
            'cart_count' => $this->cart->totalQty(),
            'cart_total' => number_format($this->cart->total(), 2),
            'subtotal'   => $item ? number_format($item->quantity * $item->product->price, 2) : '0.00',
        ]);
    }

    /** DELETE /cart/remove — AJAX */
    public function remove(Request $request): JsonResponse
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $this->cart->remove($request->integer('product_id'));

        return response()->json([
            'success'    => true,
            'message'    => 'Товар видалено',
            'cart_count' => $this->cart->totalQty(),
            'cart_total' => number_format($this->cart->total(), 2),
        ]);
    }

    /** GET /cart/mini — AJAX */
    public function mini(): JsonResponse
    {
        $items = $this->cart->items();
        $html  = view('shop.partials.mini-cart', compact('items'))->render();

        return response()->json([
            'html'       => $html,
            'cart_count' => $this->cart->totalQty(),
            'cart_total' => number_format($this->cart->total(), 2),
        ]);
    }

    /** POST /cart/checkout */
    public function checkout(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email',
            'address' => 'required|string|max:255',
            'comment' => 'nullable|string|max:500',
        ]);

        $items = $this->cart->items();
        if ($items->isEmpty()) {
            return back()->withErrors(['cart' => 'Кошик порожній']);
        }

        // Знайти або автоматично створити акаунт
        $user = Auth::user();
        $newAccountCreated = false;
        $autoPassword = null;

        if (!$user && $request->email) {
            $existingUser = User::where('email', $request->email)->first();

            if ($existingUser) {
                $user = $existingUser;
            } else {
                $autoPassword = Str::random(10);
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'password' => Hash::make($autoPassword),
                ]);
                Auth::login($user);
                $request->session()->regenerate();
                $newAccountCreated = true;
            }
        }

        $order = Order::create([
            'user_id'    => $user?->id,
            'session_id' => session()->getId(),
            'name'       => $request->name,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'address'    => $request->address,
            'comment'    => $request->comment,
            'total'      => $this->cart->total(),
        ]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item->product_id,
                'product_name' => $item->product->name,
                'price'        => $item->product->price,
                'quantity'     => $item->quantity,
            ]);
        }

        $this->cart->clear();

        return redirect()->route('shop.order-success', $order)
            ->with('success', "Замовлення #{$order->id} успішно оформлено!")
            ->with('new_account_created', $newAccountCreated)
            ->with('auto_password', $autoPassword);
    }

    /** GET /cart/success/{order} */
    public function orderSuccess(Order $order)
    {
        return view('shop.order-success', compact('order'));
    }
}
