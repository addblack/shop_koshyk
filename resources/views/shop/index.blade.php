@extends('layouts.shop')

@section('title', 'Каталог товарів — FreshMart')

@section('content')
<div class="container shop-layout">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar__card">
            <h3 class="sidebar__title">Категорії</h3>
            <ul class="category-list">
                <li>
                    <a href="{{ route('shop.index', array_filter(['search' => request('search'), 'sort' => request('sort')])) }}"
                       class="category-list__item {{ !request('category') ? 'active' : '' }}">
                        🛒 Всі товари
                        <span class="category-list__count">{{ $products->total() }}</span>
                    </a>
                </li>
                @foreach($categories as $category)
                <li>
                    <a href="{{ route('shop.index', array_filter(['category' => $category->slug, 'search' => request('search'), 'sort' => request('sort')])) }}"
                       class="category-list__item {{ request('category') === $category->slug ? 'active' : '' }}">
                        {{ $category->icon }} {{ $category->name }}
                        <span class="category-list__count">{{ $category->products_count }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </aside>

    <!-- PRODUCT AREA -->
    <div class="product-area">

        <!-- Toolbar -->
        <div class="toolbar">
            <p class="toolbar__count">Знайдено: <strong>{{ $products->total() }}</strong> товарів</p>
            <form class="toolbar__sort" id="sortForm" method="GET" action="{{ route('shop.index') }}">
                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                @if(request('search'))   <input type="hidden" name="search"   value="{{ request('search') }}"> @endif
                <label>Сортування:</label>
                <select name="sort" onchange="document.getElementById('sortForm').submit()">
                    <option value="">Новинки</option>
                    <option value="price_asc"  {{ request('sort') === 'price_asc'  ? 'selected' : '' }}>Ціна ↑</option>
                    <option value="price_desc" {{ request('sort') === 'price_desc' ? 'selected' : '' }}>Ціна ↓</option>
                </select>
            </form>
        </div>

        @if($products->isEmpty())
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <p>Товарів не знайдено</p>
                <a href="{{ route('shop.index') }}" class="btn btn--outline">Скинути фільтри</a>
            </div>
        @else
        <!-- Product Grid -->
        <div class="product-grid">
            @foreach($products as $product)
            <article class="product-card" data-id="{{ $product->id }}">
                @if($product->discount_percent)
                    <span class="product-card__badge">−{{ $product->discount_percent }}%</span>
                @endif

                <a href="{{ route('shop.product', $product->slug) }}" class="product-card__img-wrap">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
                    @else
                        <div class="product-card__placeholder">{{ $product->icon ?? $product->category->icon ?? '🛍' }}</div>
                    @endif
                </a>

                <div class="product-card__body">
                    <span class="product-card__category">{{ $product->category->name }}</span>
                    <a href="{{ route('shop.product', $product->slug) }}" class="product-card__title">
                        {{ $product->name }}
                    </a>

                    <div class="product-card__footer">
                        <div class="product-card__price">
                            <span class="price--current">{{ number_format($product->price, 2) }} ₴</span>
                            @if($product->old_price)
                                <span class="price--old">{{ number_format($product->old_price, 2) }} ₴</span>
                            @endif
                            <span class="price--unit">/ {{ $product->unit }}</span>
                        </div>

                        <div class="product-card__qty-control" style="display:none;" data-product="{{ $product->id }}">
                            <button class="qty-btn qty-minus" data-product="{{ $product->id }}">−</button>
                            <span class="qty-value" id="qty-{{ $product->id }}">1</span>
                            <button class="qty-btn qty-plus" data-product="{{ $product->id }}">+</button>
                        </div>

                        <button class="btn btn--add btn--primary add-to-cart"
                                data-product="{{ $product->id }}"
                                data-name="{{ $product->name }}">
                            <i class="fas fa-plus"></i> До кошика
                        </button>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="pagination-wrap">
            {{ $products->links('shop.partials.pagination') }}
        </div>
        @endif
        @endif

    </div><!-- /product-area -->
</div><!-- /container -->
@endsection
