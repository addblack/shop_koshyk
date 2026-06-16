@extends('layouts.shop')
@section('title', 'Замовлення прийнято — FreshMart')

@section('content')
<div class="container">
    <div class="success-page">
        <div class="success-icon">✅</div>
        <h1>Дякуємо за замовлення!</h1>
        <p class="success-sub">Ваше замовлення <strong>#{{ $order->id }}</strong> успішно оформлено.</p>
        <p>Ми зв'яжемося з вами за номером <strong>{{ $order->phone }}</strong> для підтвердження доставки.</p>

        <div class="order-details">
            <div class="order-details__row"><span>Отримувач:</span><strong>{{ $order->name }}</strong></div>
            <div class="order-details__row"><span>Адреса:</span><strong>{{ $order->address }}</strong></div>
            <div class="order-details__row"><span>Сума:</span><strong>{{ number_format($order->total, 2) }} ₴</strong></div>
        </div>

        <div class="success-actions">
            <a href="{{ route('shop.index') }}" class="btn btn--primary">
                <i class="fas fa-store"></i> Продовжити покупки
            </a>
        </div>
    </div>
</div>
@endsection
