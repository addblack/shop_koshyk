<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;

class CartService
{
    private string $sessionId;

    public function __construct()
    {
        $this->sessionId = Session::getId();
    }

    /** Усі позиції кошика з підвантаженими продуктами */
    public function items(): Collection
    {
        return CartItem::where('session_id', $this->sessionId)
            ->with('product.category')
            ->get();
    }

    /** Кількість унікальних позицій */
    public function count(): int
    {
        return CartItem::where('session_id', $this->sessionId)->count();
    }

    /** Загальна кількість одиниць товарів */
    public function totalQty(): int
    {
        return (int) CartItem::where('session_id', $this->sessionId)->sum('quantity');
    }

    /** Загальна сума */
    public function total(): float
    {
        return $this->items()->sum(fn ($item) => $item->quantity * $item->product->price);
    }

    /** Додати товар або збільшити кількість */
    public function add(int $productId, int $qty = 1): CartItem
    {
        $item = CartItem::firstOrCreate(
            ['session_id' => $this->sessionId, 'product_id' => $productId],
            ['quantity' => 0]
        );
        $item->increment('quantity', $qty);
        $item->load('product');
        return $item->fresh('product');
    }

    /** Встановити конкретну кількість */
    public function update(int $productId, int $qty): ?CartItem
    {
        if ($qty <= 0) {
            $this->remove($productId);
            return null;
        }

        $item = CartItem::where('session_id', $this->sessionId)
            ->where('product_id', $productId)
            ->firstOrFail();
        $item->update(['quantity' => $qty]);
        return $item->fresh('product');
    }

    /** Видалити позицію */
    public function remove(int $productId): void
    {
        CartItem::where('session_id', $this->sessionId)
            ->where('product_id', $productId)
            ->delete();
    }

    /** Очистити кошик */
    public function clear(): void
    {
        CartItem::where('session_id', $this->sessionId)->delete();
    }
}
