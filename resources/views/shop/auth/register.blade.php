@extends('layouts.shop')
@section('title', 'Реєстрація — FreshMart')

@section('content')
<div class="container">
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-card__logo">🛒 FreshMart</div>
            <h1 class="auth-card__title">Реєстрація</h1>

            @if($errors->any())
                <div class="alert alert--error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('shop.register') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Ім'я</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}"
                           placeholder="Ваше ім'я" autofocus required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email') }}"
                           placeholder="your@email.com" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Пароль</label>
                    <input type="password" name="password" class="form-input"
                           placeholder="Мінімум 6 символів" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Підтвердження паролю</label>
                    <input type="password" name="password_confirmation" class="form-input"
                           placeholder="Повторіть пароль" required>
                </div>
                <button type="submit" class="btn btn--primary btn--block">Зареєструватись</button>
            </form>

            <p class="auth-card__switch">
                Вже є акаунт?
                <a href="{{ route('shop.login') }}">Увійти</a>
            </p>
        </div>
    </div>
</div>
@endsection
