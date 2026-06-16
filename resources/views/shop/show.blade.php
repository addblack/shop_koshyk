@extends('layouts.shop')

@section('title', $product->name . ' — FreshMart')

@section('content')
<div class="container">

    <!-- Breadcrumb -->
    <nav class="breadcrumb">
        <a href="{{ route('shop.index') }}">Каталог</a>
        <span>/</span>
        <a href="{{ route('shop.index', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a>
        <span>/</span>
        <span>{{ $product->name }}</span>
    </nav>

    <!-- Product Detail -->
    <div class="product-detail">

        <!-- Image -->
        <div class="product-detail__img">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
            @else
                <div class="product-detail__placeholder">
                    {{ $product->icon ?? $product->category->icon ?? '🛍' }}
                </div>
            @endif
            @if($product->discount_percent)
                <span class="product-card__badge product-card__badge--lg">−{{ $product->discount_percent }}%</span>
            @endif
        </div>

        <!-- Info -->
        <div class="product-detail__info">
            <span class="product-card__category">{{ $product->category->name }}</span>
            <h1 class="product-detail__title">{{ $product->name }}</h1>

            <div class="product-detail__price">
                <span class="price--current price--lg">{{ number_format($product->price, 2) }} ₴</span>
                @if($product->old_price)
                    <span class="price--old">{{ number_format($product->old_price, 2) }} ₴</span>
                @endif
                <span class="price--unit">/ {{ $product->unit }}</span>
            </div>

            @if($product->description)
                <p class="product-detail__desc">{{ $product->description }}</p>
            @endif

            <div class="product-detail__stock">
                @if($product->stock > 0)
                    <span class="stock--in">✓ В наявності</span>
                @else
                    <span class="stock--out">✗ Немає в наявності</span>
                @endif
            </div>

            <div class="product-detail__actions">
                <div class="qty-control qty-control--lg">
                    <button class="qty-btn" id="detailMinus">−</button>
                    <input class="qty-input" type="number" id="detailQty" value="1" min="1" max="99">
                    <button class="qty-btn" id="detailPlus">+</button>
                </div>

                <button class="btn btn--primary btn--lg add-to-cart"
                        data-product="{{ $product->id }}"
                        data-name="{{ $product->name }}"
                        id="detailAddBtn">
                    <i class="fas fa-shopping-cart"></i> Додати до кошика
                </button>
            </div>
        </div>
    </div>

    <!-- Related products -->
    @if($related->isNotEmpty())
    <section class="related">
        <h2 class="related__title">Схожі товари</h2>
        <div class="product-grid">
            @foreach($related as $product)
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
                    <a href="{{ route('shop.product', $product->slug) }}" class="product-card__title">{{ $product->name }}</a>
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
    </section>
    @endif

</div>
@endsection

@push('scripts')
<script>
document.getElementById('detailMinus')?.addEventListener('click', () => {
    const input = document.getElementById('detailQty');
    input.value = Math.max(1, parseInt(input.value) - 1);
});
document.getElementById('detailPlus')?.addEventListener('click', () => {
    const input = document.getElementById('detailQty');
    input.value = Math.min(99, parseInt(input.value) + 1);
});
document.getElementById('detailAddBtn')?.addEventListener('click', function() {
    const qty = parseInt(document.getElementById('detailQty').value) || 1;
    Cart.addToCart({{ $product->id }}, qty, this);
});
</script>
@endpush
