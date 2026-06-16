@if($items->isEmpty())
    <div class="mini-cart__empty">
        <i class="fas fa-shopping-basket"></i>
        <p>Кошик порожній</p>
    </div>
@else
    <ul class="mini-cart__list">
        @foreach($items as $item)
        <li class="mini-cart__item">
            <span class="mini-cart__emoji">{{ $item->product->category->icon ?? '🛍' }}</span>
            <div class="mini-cart__info">
                <span class="mini-cart__name">{{ $item->product->name }}</span>
                <span class="mini-cart__qty">{{ $item->quantity }} × {{ number_format($item->product->price, 2) }} ₴</span>
            </div>
            <span class="mini-cart__sub">{{ number_format($item->quantity * $item->product->price, 2) }} ₴</span>
        </li>
        @endforeach
    </ul>
@endif
