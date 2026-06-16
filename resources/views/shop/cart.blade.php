@extends('layouts.shop')

@section('title', 'Кошик — FreshMart')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>🛒 Мій кошик</h1>
        <a href="{{ route('shop.index') }}" class="btn btn--outline btn--sm">
            <i class="fas fa-arrow-left"></i> Продовжити покупки
        </a>
    </div>

    @if($items->isEmpty())
        <div class="empty-state empty-state--large">
            <i class="fas fa-shopping-cart"></i>
            <h2>Кошик порожній</h2>
            <p>Додайте товари з нашого каталогу</p>
            <a href="{{ route('shop.index') }}" class="btn btn--primary">Перейти до каталогу</a>
        </div>
    @else
    <div class="cart-layout">

        <!-- CART TABLE -->
        <div class="cart-table-wrap">
            <table class="cart-table" id="cartTable">
                <thead>
                    <tr>
                        <th colspan="2">Товар</th>
                        <th>Ціна</th>
                        <th>Кількість</th>
                        <th>Сума</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="cartBody">
                    @foreach($items as $item)
                    <tr class="cart-row" id="cart-row-{{ $item->product_id }}" data-product="{{ $item->product_id }}">
                        <td class="cart-row__img">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                            @else
                                <div class="cart-row__emoji">{{ $item->product->icon ?? $item->product->category->icon ?? '🛍' }}</div>
                            @endif
                        </td>
                        <td class="cart-row__name">
                            <a href="{{ route('shop.product', $item->product->slug) }}">{{ $item->product->name }}</a>
                            <small>{{ $item->product->category->name }}</small>
                        </td>
                        <td class="cart-row__price">{{ number_format($item->product->price, 2) }} ₴</td>
                        <td class="cart-row__qty">
                            <div class="qty-control">
                                <button class="qty-btn cart-minus" data-product="{{ $item->product_id }}">−</button>
                                <input class="qty-input" type="number"
                                       id="cart-qty-{{ $item->product_id }}"
                                       value="{{ $item->quantity }}"
                                       min="1" max="99"
                                       data-product="{{ $item->product_id }}">
                                <button class="qty-btn cart-plus" data-product="{{ $item->product_id }}">+</button>
                            </div>
                        </td>
                        <td class="cart-row__subtotal" id="subtotal-{{ $item->product_id }}">
                            {{ number_format($item->quantity * $item->product->price, 2) }} ₴
                        </td>
                        <td class="cart-row__remove">
                            <button class="remove-btn cart-remove" data-product="{{ $item->product_id }}" title="Видалити">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="cart-table__actions">
                <button class="btn btn--outline btn--danger" id="clearCart">
                    <i class="fas fa-trash"></i> Очистити кошик
                </button>
            </div>
        </div>

        <!-- SIDEBAR: TOTAL + CHECKOUT -->
        <aside class="cart-sidebar">
            <div class="cart-summary">
                <h3>Підсумок замовлення</h3>
                <div class="cart-summary__row">
                    <span>Кількість товарів:</span>
                    <span id="summaryCount">{{ $items->count() }}</span>
                </div>
                <div class="cart-summary__row cart-summary__row--total">
                    <span>До сплати:</span>
                    <strong id="summaryTotal">{{ number_format($total, 2) }} ₴</strong>
                </div>
            </div>

            <!-- CHECKOUT FORM -->
            <form class="checkout-form" action="{{ route('shop.checkout') }}" method="POST" id="checkoutForm">
                @csrf
                <h3>Оформлення замовлення</h3>

                <div class="form-group">
                    <label>Ваше ім'я *</label>
                    <input type="text" name="name" required placeholder="Іван Петренко"
                           value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror">
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Телефон *</label>
                    <input type="tel" name="phone" required placeholder="+380 XX XXX XX XX"
                           value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror">
                    @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="email@example.com"
                           value="{{ old('email') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label>Адреса доставки *</label>
                    <input type="text" name="address" required placeholder="м. Київ, вул. Хрещатик, 1"
                           value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror">
                    @error('address') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label>Коментар до замовлення</label>
                    <textarea name="comment" class="form-control" rows="2"
                              placeholder="Побажання...">{{ old('comment') }}</textarea>
                </div>

                <button type="submit" class="btn btn--primary btn--full btn--lg">
                    <i class="fas fa-check-circle"></i> Оформити замовлення
                </button>
            </form>
        </aside>

    </div>
    @endif
</div>
@endsection
