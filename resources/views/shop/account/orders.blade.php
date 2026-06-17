@extends('layouts.shop')
@section('title', 'Мої замовлення — FreshMart')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>👤 Особистий кабінет</h1>
        <form method="POST" action="{{ route('shop.logout') }}" style="display:inline">
            @csrf
            <button type="submit" class="btn btn--outline btn--sm">
                <i class="fas fa-sign-out-alt"></i> Вийти
            </button>
        </form>
    </div>

    <div class="account-info">
        <strong>{{ Auth::user()->name }}</strong>
        <span class="account-info__email">{{ Auth::user()->email }}</span>
    </div>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    <h2 class="section-title" style="margin-top:32px">Мої замовлення</h2>

    @if($orders->isEmpty())
        <div class="empty-state">
            <i class="fas fa-box-open"></i>
            <h3>Замовлень ще немає</h3>
            <a href="{{ route('shop.index') }}" class="btn btn--primary">Перейти до каталогу</a>
        </div>
    @else
        <div class="orders-list">
            @foreach($orders as $order)
            <div class="order-card">
                <div class="order-card__header">
                    <div class="order-card__id">Замовлення #{{ $order->id }}</div>
                    <span class="status-badge {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
                    <div class="order-card__date">{{ $order->created_at->format('d.m.Y H:i') }}</div>
                </div>
                <div class="order-card__items">
                    @foreach($order->items as $item)
                    <div class="order-card__item">
                        <span class="order-card__item-name">{{ $item->product_name }}</span>
                        <span class="order-card__item-qty">× {{ $item->quantity }}</span>
                        <span class="order-card__item-price">{{ number_format($item->price * $item->quantity, 2) }} ₴</span>
                    </div>
                    @endforeach
                </div>
                <div class="order-card__footer">
                    <span>Адреса: {{ $order->address }}</span>
                    <strong class="order-card__total">Разом: {{ number_format($order->total, 2) }} ₴</strong>
                </div>
            </div>
            @endforeach
        </div>

        {{ $orders->links('shop.partials.pagination') }}
    @endif
</div>
@endsection
