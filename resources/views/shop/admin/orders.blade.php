@extends('layouts.shop')
@section('title', 'Адмін — Замовлення — FreshMart')

@section('content')
<div class="container">
    <div class="page-header">
        <h1>🛠 Адмін-панель — Замовлення</h1>
        <a href="{{ route('shop.index') }}" class="btn btn--outline btn--sm">
            <i class="fas fa-store"></i> До магазину
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert--success">{{ session('success') }}</div>
    @endif

    <!-- СТАТИСТИКА -->
    <div class="admin-stats">
        <a href="{{ route('shop.admin.orders') }}" class="stat-card {{ !request('status') ? 'stat-card--active' : '' }}">
            <div class="stat-card__num">{{ $counts['all'] }}</div>
            <div class="stat-card__label">Всього</div>
        </a>
        <a href="{{ route('shop.admin.orders', ['status' => 'new']) }}" class="stat-card status--new {{ request('status') === 'new' ? 'stat-card--active' : '' }}">
            <div class="stat-card__num">{{ $counts['new'] }}</div>
            <div class="stat-card__label">Нових</div>
        </a>
        <a href="{{ route('shop.admin.orders', ['status' => 'processing']) }}" class="stat-card {{ request('status') === 'processing' ? 'stat-card--active' : '' }}">
            <div class="stat-card__num">{{ $counts['processing'] }}</div>
            <div class="stat-card__label">В обробці</div>
        </a>
        <a href="{{ route('shop.admin.orders', ['status' => 'delivered']) }}" class="stat-card {{ request('status') === 'delivered' ? 'stat-card--active' : '' }}">
            <div class="stat-card__num">{{ $counts['delivered'] }}</div>
            <div class="stat-card__label">Доставлено</div>
        </a>
        <a href="{{ route('shop.admin.orders', ['status' => 'cancelled']) }}" class="stat-card {{ request('status') === 'cancelled' ? 'stat-card--active' : '' }}">
            <div class="stat-card__num">{{ $counts['cancelled'] }}</div>
            <div class="stat-card__label">Скасовано</div>
        </a>
    </div>

    <!-- ПОШУК -->
    <form method="GET" action="{{ route('shop.admin.orders') }}" class="admin-search">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <input type="text" name="search" class="form-input" placeholder="Пошук за ім'ям, email, телефоном, #ID..."
               value="{{ request('search') }}">
        <button type="submit" class="btn btn--primary btn--sm">
            <i class="fas fa-search"></i> Знайти
        </button>
        @if(request('search'))
        <a href="{{ route('shop.admin.orders', request()->except('search', 'page')) }}" class="btn btn--outline btn--sm">
            <i class="fas fa-times"></i> Скинути
        </a>
        @endif
    </form>

    <!-- ТАБЛИЦЯ -->
    @if($orders->isEmpty())
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>Замовлень не знайдено</h3>
        </div>
    @else
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Клієнт</th>
                    <th>Товари</th>
                    <th>Сума</th>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Дія</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td><strong>#{{ $order->id }}</strong></td>
                    <td>
                        <div class="admin-client">
                            <strong>{{ $order->name }}</strong>
                            @if($order->email)
                                <small>{{ $order->email }}</small>
                            @endif
                            <small>{{ $order->phone }}</small>
                            <small class="text-muted">{{ $order->address }}</small>
                            @if($order->comment)
                                <small class="text-muted"><i>{{ $order->comment }}</i></small>
                            @endif
                        </div>
                    </td>
                    <td>
                        <ul class="admin-items">
                            @foreach($order->items as $item)
                            <li>{{ $item->product_name }} × {{ $item->quantity }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td><strong>{{ number_format($order->total, 2) }} ₴</strong></td>
                    <td>{{ $order->created_at->format('d.m.Y') }}<br><small>{{ $order->created_at->format('H:i') }}</small></td>
                    <td>
                        <span class="status-badge {{ $order->statusClass() }}">{{ $order->statusLabel() }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('shop.admin.orders.status', $order) }}">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-input form-input--sm" onchange="this.form.submit()">
                                <option value="new"        {{ $order->status === 'new'        ? 'selected' : '' }}>Нове</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>В обробці</option>
                                <option value="delivered"  {{ $order->status === 'delivered'  ? 'selected' : '' }}>Доставлено</option>
                                <option value="cancelled"  {{ $order->status === 'cancelled'  ? 'selected' : '' }}>Скасовано</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $orders->links('shop.partials.pagination') }}
    @endif
</div>
@endsection
