<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FreshMart — Продуктовий магазин')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/shop.css') }}">
    @stack('styles')
</head>
<body>

<!-- HEADER -->
<header class="header">
    <div class="container header__inner">
        <a href="{{ route('shop.index') }}" class="header__logo">
            🛒 <span>Fresh<strong>Mart</strong></span>
        </a>

        <form class="header__search" action="{{ route('shop.index') }}" method="GET">
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <input type="text" name="search" placeholder="Пошук товарів..." value="{{ request('search') }}">
            <button type="submit"><i class="fas fa-search"></i></button>
        </form>

        <div class="header__actions">
            <!-- Акаунт -->
            @auth
                <div class="header__user-menu">
                    <button class="user-btn" id="userMenuToggle">
                        <i class="fas fa-user-circle"></i>
                        <span class="user-btn__name">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down" style="font-size:10px"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown">
                        <a href="{{ route('shop.account.orders') }}" class="user-dropdown__item">
                            <i class="fas fa-list"></i> Мої замовлення
                        </a>
                        @if(Auth::user()->is_admin)
                        <a href="{{ route('shop.admin.orders') }}" class="user-dropdown__item user-dropdown__item--admin">
                            <i class="fas fa-cog"></i> Адмін-панель
                        </a>
                        @endif
                        <form method="POST" action="{{ route('shop.logout') }}">
                            @csrf
                            <button type="submit" class="user-dropdown__item user-dropdown__item--logout">
                                <i class="fas fa-sign-out-alt"></i> Вийти
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('shop.login') }}" class="btn btn--outline btn--sm">
                    <i class="fas fa-sign-in-alt"></i> Увійти
                </a>
            @endauth

            <button class="cart-btn" id="cartToggle" aria-label="Кошик">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-badge" id="cartBadge">{{ $cart->totalQty() }}</span>
            </button>
            <a href="{{ route('shop.cart') }}" class="btn btn--primary btn--sm">
                Кошик
            </a>
        </div>
    </div>
</header>

<!-- MINI CART DROPDOWN -->
<div class="mini-cart-overlay" id="miniCartOverlay"></div>
<div class="mini-cart" id="miniCart">
    <div class="mini-cart__header">
        <h3>Кошик</h3>
        <button class="mini-cart__close" id="miniCartClose"><i class="fas fa-times"></i></button>
    </div>
    <div class="mini-cart__body" id="miniCartBody">
        <div class="mini-cart__loading"><i class="fas fa-spinner fa-spin"></i></div>
    </div>
    <div class="mini-cart__footer" id="miniCartFooter"></div>
</div>

<!-- TOAST -->
<div class="toast-container" id="toastContainer"></div>

<!-- MAIN -->
<main class="main">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="footer">
    <div class="container">
        <p>© {{ date('Y') }} FreshMart. Електронний кошик продуктового магазину.</p>
        <p class="footer__sub">Дипломна робота · Спеціальність «Інженерія програмного забезпечення»</p>
    </div>
</footer>

<script src="{{ asset('js/shop.js') }}"></script>
@stack('scripts')
</body>
</html>
