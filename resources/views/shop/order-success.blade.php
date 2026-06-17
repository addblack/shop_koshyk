@extends('layouts.shop')
@section('title', 'Замовлення прийнято — FreshMart')

@section('content')
<div class="container">
    <div class="success-page">
        <div class="success-icon">✅</div>
        <h1>Дякуємо за замовлення!</h1>
        <p class="success-sub">Ваше замовлення <strong>#{{ $order->id }}</strong> успішно оформлено.</p>
        <p>Ми зв'яжемося з вами за номером <strong>{{ $order->phone }}</strong> для підтвердження доставки.</p>

        @if(session('new_account_created') && session('auto_password'))
        <div class="alert alert--info" style="margin: 24px 0; text-align: left;">
            <strong>🎉 Для вас автоматично створено акаунт!</strong><br>
            Тепер ви можете відстежувати свої замовлення в особистому кабінеті.<br><br>
            <strong>Email:</strong> {{ $order->email }}<br>
            <strong>Пароль:</strong> <code style="background:#f0f0f0;padding:2px 6px;border-radius:4px;">{{ session('auto_password') }}</code><br>
            <small>Збережіть пароль — він більше не відображатиметься.</small>
        </div>
        @endif

        <div class="order-details">
            <div class="order-details__row"><span>Отримувач:</span><strong>{{ $order->name }}</strong></div>
            <div class="order-details__row"><span>Адреса:</span><strong>{{ $order->address }}</strong></div>
            <div class="order-details__row"><span>Сума:</span><strong>{{ number_format($order->total, 2) }} ₴</strong></div>
            <div class="order-details__row"><span>Статус:</span>
                <span class="status-badge {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
            </div>
        </div>

        <div class="success-actions">
            @auth
            <a href="{{ route('shop.account.orders') }}" class="btn btn--outline">
                <i class="fas fa-list"></i> Мої замовлення
            </a>
            @endauth
            <a href="{{ route('shop.index') }}" class="btn btn--primary">
                <i class="fas fa-store"></i> Продовжити покупки
            </a>
        </div>
    </div>
</div>
@endsection
